<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\EventStep;
use App\Models\TeamMember;
use Illuminate\Support\Str;
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

class EksternalAssetBookingsExport implements WithMultipleSheets
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
    protected array $headers = ['No.', 'Nomor Booking', 'Nama Aset', 'Nama Peminjam', 'Nama Event', 'Kategori Booking', 'Penggunaan', 'Tipe Pembayaran', 'Jumlah Bayar', 'Sisa Bayar', 'Status', 'Keterangan'];

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

        $bookings = AssetBooking::with('transactions')->whereNotNull('asset_category_id')
            ->where(function ($query) {
                $query->whereBetween('usage_date_start', [$this->monthStart, $this->monthEnd])
                    ->orWhereBetween('usage_date_end', [$this->monthStart, $this->monthEnd])
                    ->orWhere(function ($q) {
                        $q->where('usage_date_start', '<', $this->monthStart)
                            ->where('usage_date_end', '>', $this->monthEnd);
                    });
            })->get();

        $data = collect();
        $i = 1;
        foreach ($bookings as $booking) {
            $statusText = '';

            // Ambil relasi transactions sebagai Collection
            $transactions = $booking->transactions; // asumsikan eager loaded
            $remaining_payment = 0;
            if ($booking->payment_type === 'dp') {
                if ($transactions->count() > 1) {
                    $remaining_payment = '-';
                } else {
                    $transaction = $transactions->first();
                    $remaining_payment = $transaction
                        ? $booking->total_amount - $transaction->amount
                        : $booking->total_amount; // fallback jika tidak ada transaksi
                }
            } else {
                $transaction = $transactions->first();
                $remaining_payment = $transaction ? '-' : $booking->total_amount;
            }

            if (Str::contains($booking->status, 'submission')) {
                $statusText = 'Proses Pengajuan Booking';
            } elseif ($booking->status === 'booked') {
                $statusText = 'Booking Disetujui';
            } elseif ($booking->status === 'approved_dp_payment') {
                $statusText = 'Pembayaran DP Disetujui';
            } elseif ($booking->status === 'approved_full_payment') {
                $statusText = 'Pembayaran Lunas Disetujui';
            } elseif ($booking->status === 'rejected_dp_payment') {
                $statusText = 'Pembayaran DP Ditolak';
            } elseif ($booking->status === 'rejected_full_payment') {
                $statusText = 'Pembayaran Lunas Ditolak';
            } elseif ($booking->status === 'cancelled') {
                $statusText = 'Peminjaman Dibatalkan';
            } else {
                $statusText = $booking->status;
            }

            $data->push([
                $i++,
                $booking->booking_number,
                $booking->asset->name ?? '-',
                $booking->user->name ??  $booking->external_user,
                $booking->event->name ?? $booking->usage_event_name,
                $booking->asset_category->category_name ?? '-',
                Carbon::parse($booking->usage_date_start)->format('d-m-Y H:i') . ' - ' .
                    Carbon::parse($booking->usage_date_end)->format('d-m-Y H:i'),
                strtoupper($booking->payment_type),
                $booking->total_amount,
                $remaining_payment,
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
                $event->sheet->getStyle('I2:I' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('_(Rp* #,##0.00_);_(Rp* (#,##0.00);_(Rp* "-"??_);_(@_)');
                $event->sheet->getStyle('J2:J' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('_(Rp* #,##0.00_);_(Rp* (#,##0.00);_(Rp* "-"??_);_(@_)');
            },
        ];
    }
}