<?php

namespace App\Exports;

use App\Models\EventStep;
use App\Models\TeamMember;
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

class EventAttendanceParticipantsExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected string $eventId;
    protected string $category;

    public function __construct(string $eventId, string $category)
    {
        $this->eventId = $eventId;
        $this->category = $category;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Ambil semua step
        $steps = EventStep::where('event_id', $this->eventId)->get();
        foreach ($steps as $step) {
            $sheets[] = new EventAttendanceByStepSheet($this->category, $this->eventId, $step->id, $step->step_name ?? $step->event->title);
        }

        return $sheets;
    }
}
class EventAttendanceByStepSheet implements FromCollection, ShouldAutoSize, WithTitle, WithHeadings, WithEvents
{
    protected string $category;
    protected string $eventId;
    protected string $stepId;
    protected string $stepName;

    protected array $headers;
    public function __construct(string $category, string $eventId, string $stepId, string $stepName)
    {
        $this->category = $category;
        $this->eventId = $eventId;
        $this->stepId = $stepId;
        $this->stepName = $stepName;
    }
    public function title(): string
    {
        return ucfirst($this->stepName);
    }
    public function headings(): array
    {
        if ($this->category === 'participant') {
            $this->headers = ['No.', 'Kode Tiket', 'Nama Peserta', 'Asal', 'Presensi Datang', 'Presensi Pulang'];
            return ['No.', 'Kode Tiket', 'Nama Peserta', 'Asal', 'Presensi Datang', 'Presensi Pulang'];
        }
        $this->headers = ['No.', 'Nama', 'Jabatan', 'Presensi Datang', 'Presensi Pulang'];
        return ['No.', 'Nama', 'Jabatan', 'Presensi Datang', 'Presensi Pulang'];
    }

    public function collection(): Collection
    {
        $data = collect();
        $i = 1;

        if ($this->category === 'participant') {
            $participants = EventParticipant::with(['attendances' => function ($q) {
                $q->where('event_step_id', $this->stepId);
            }])->where('event_id', $this->eventId)->get();

            foreach ($participants as $participant) {
                $attendance = $participant->attendances->first();

                $arrival = optional($attendance)->attendance_arrival
                    ? 'Hadir (' . optional($attendance->attendance_arrival_time)->format('H:i') . ')'
                    : '-';

                $departure = optional($attendance)->attendance_departure
                    ? 'Hadir (' . optional($attendance->attendance_departure_time)->format('H:i') . ')'
                    : '-';

                $data->push([
                    $i++,
                    $participant->ticket_code,
                    $participant->user->name,
                    $participant->user->category_user == 'Internal Kampus' ? 'Mahasiswa J' . $participant->user->jurusan->kode_jurusan : 'Eksternal Kampus',
                    $arrival,
                    $departure,
                ]);
            }
        } else {
            $organizerId = Auth::user()->organizer->id;
            $members = TeamMember::with(['attendances' => function ($q) {
                $q->where('event_step_id', $this->stepId);
            }])->where('organizer_id', $organizerId)->get();

            foreach ($members as $member) {
                $attendance = $member->attendances->first();

                $arrival = optional($attendance)->attendance_arrival
                    ? 'Hadir (' . optional($attendance->attendance_arrival_time)->format('H:i') . ')'
                    : '-';

                $departure = optional($attendance)->attendance_departure
                    ? 'Hadir (' . optional($attendance->attendance_departure_time)->format('H:i') . ')'
                    : '-';

                $data->push([
                    $i++,
                    $member->name,
                    $member->position,
                    $arrival,
                    $departure,
                ]);
            }
        }

        return $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = count($this->headers) + 64; // Hitung jumlah kolom dan konversi ke huruf ASCII (A=65, B=66, dst.)
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                // Loop through range of columns dynamically based on the number of columns in the header
                foreach (range('A', chr($lastColumn)) as $column) {
                    // Set align horizontal for heading to center
                    $event->sheet->getStyle($column)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Set align vertical for all cells to center
                    $event->sheet->getStyle($column)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }
                // Loop through each row starting from the second row (index 2)
                for ($row = 2; $row <= $lastRow; $row++) {
                    // Loop through range of columns dynamically based on the number of columns in the header
                    foreach (range('A', chr($lastColumn)) as $column) {
                        // Set align horizontal for all cells to left except for the first row
                        if ($row > 1) {
                            $event->sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        }
                    }
                }
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                // Adjust according to the last column and row
                if ($this->category === 'participant') {
                    $event->sheet->setAutoFilter('D1');
                }

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('A1:F1')->getFont()->setBold(true);

                // Apply other styles as needed
                $event->sheet->getStyle('A1:' . chr($lastColumn) . $lastRow)
                    ->applyFromArray([
                        'font' => [
                            'size' => 12,
                            'name' => 'Times New Roman',
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
