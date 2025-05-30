<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\EventStep;
use App\Models\TeamMember;
use App\Models\AssetBooking;
use App\Models\EventAttendance;
use App\Models\EventParticipant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InternalAssetBookingsExport implements WithMultipleSheets
{
    protected string $eventId;
    protected string $category;
    protected Carbon $yearStart;
    protected Carbon $yearEnd;

    public function __construct(Carbon $yearStart, Carbon $yearEnd)
    {
        $this->yearStart = $yearStart;
        $this->yearEnd = $yearEnd;
    }

    public function sheets(): array
    {
        $sheets = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::createFromDate($this->yearStart->year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($this->yearStart->year, $month, 1)->endOfMonth();

            $sheets[] = new BookingByMonthSheet(
                $month,
                $monthStart,
                $monthEnd
            );
        }

        return $sheets;
    }
}
class BookingByMonthSheet implements FromCollection, ShouldAutoSize, WithTitle, WithHeadings, WithEvents
{

    protected int $month;
    protected Carbon $monthStart;
    protected Carbon $monthEnd;
    protected array $headers = ['No.', 'Nomor Booking', 'Nama Aset', 'Nama Peminjam', 'Nama Event', 'Penggunaan', 'Status', 'Keterangan'];

    public function __construct(int $month, Carbon $monthStart, Carbon $monthEnd)
    {
        $this->month = $month;
        $this->monthStart = $monthStart;
        $this->monthEnd = $monthEnd;
    }

    public function title(): string
    {
        return $this->monthStart->format('F'); // Misal: 'January', 'February'
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function collection(): Collection
    {
        $role = auth()->user()->getRoleNames()->first(); // contoh ambil role user
        $userJurusanId = auth()->user()->jurusan_id; // contoh ambil jurusan id user

        $bookings = AssetBooking::whereNull('asset_category_id')
            ->where(function ($query) {
                $query->whereBetween('usage_date_start', [$this->monthStart, $this->monthEnd])
                    ->orWhereBetween('usage_date_end', [$this->monthStart, $this->monthEnd])
                    ->orWhere(function ($q) {
                        $q->where('usage_date_start', '<', $this->monthStart)
                            ->where('usage_date_end', '>', $this->monthEnd);
                    });
            });

        if ($role === 'Admin Jurusan') {
            $bookings->whereHas('asset', function ($q) use ($userJurusanId) {
                $q->where('jurusan_id', $userJurusanId);
            });
        }

        $bookings = $bookings->get();


        $data = collect();
        $i = 1;
        foreach ($bookings as $booking) {
            $statusText = '';

            switch ($booking->status) {
                case 'submission_booking':
                    $statusText = 'Proses Pengajuan Booking';
                    break;
                case 'booked':
                    $statusText = 'Booking Disetujui';
                    break;
                case 'submission_full_payment':
                    $statusText = 'Pengajuan Surat Peminjaman';
                    break;
                case 'approved':
                    $statusText = 'Peminjaman Disetujui';
                    break;
                case 'rejected':
                    $statusText = 'Peminjaman Ditolak';
                    break;
                case 'rejected_full_payment':
                    $statusText = 'Surat Peminjaman Ditolak';
                    break;
                case 'cancelled':
                    $statusText = 'Peminjaman Dibatalkan';
                    break;
            }
            $data->push([
                $i++,
                $booking->booking_number,
                $booking->asset->name ?? '-',
                $booking->user->name ??  $booking->external_user,
                $booking->event->name ?? $booking->usage_event_name,
                Carbon::parse($booking->usage_date_start)->format('d-m-Y H:i') . ' - ' .
                    Carbon::parse($booking->usage_date_end)->format('d-m-Y H:i'),
                $statusText,
                $booking->reason ?? '-',
            ]);
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = chr(count($this->headers) + 64);
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                foreach (range('A', $lastColumn) as $column) {
                    $event->sheet->getStyle($column)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getStyle($column)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

                for ($row = 2; $row <= $lastRow; $row++) {
                    foreach (range('A', $lastColumn) as $column) {
                        $event->sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    }
                }

                $event->sheet->setAutoFilter('C1');
                $event->sheet->getRowDimension(1)->setRowHeight(30);

                $event->sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'font' => [
                        'size' => 12,
                        'name' => 'Times New Roman',
                        'bold' => false,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
