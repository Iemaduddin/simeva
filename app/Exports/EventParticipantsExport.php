<?php

namespace App\Exports;

use App\Models\EventStep;
use App\Models\TeamMember;
use App\Models\EventAttendance;
use App\Models\EventParticipant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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

class EventParticipantsExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected string $eventId;
    protected string $category;
    protected $header;
    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
    public function headings(): array
    {
        $this->header = ['No.', 'Kode Tiket', 'Nama Lengkap', 'Asal Pengguna', 'Alamat'];
        return $this->header;
    }

    public function collection(): Collection
    {
        $data = collect();
        $i = 1;
        function readCsvPublic(string $relativePath): array
        {
            $rows = [];
            $fullPath = public_path('storage/wilayah/' . $relativePath);

            if (!file_exists($fullPath)) {
                \Log::error("CSV not found: $fullPath");
                return $rows;
            }

            if (($handle = fopen($fullPath, 'r')) !== false) {
                $header = fgetcsv($handle, 1000, ','); // baca baris pertama (header)
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($row) === count($header)) {
                        $rows[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }

            return $rows;
        }

        function findNameById(array $list, $id): string
        {
            foreach ($list as $item) {
                if ($item['id'] == $id) {
                    return $item['name'];
                }
            }
            return '';
        }
        $provinces  = collect(readCsvPublic('provinces.csv'))->keyBy('id');
        $regencies  = collect(readCsvPublic('regencies.csv'))->keyBy('id');
        $districts  = collect(readCsvPublic('districts.csv'))->keyBy('id');
        $villages   = collect(readCsvPublic('villages.csv'))->keyBy('id');

        $participants = EventParticipant::with('user.jurusan')->where('event_id', $this->eventId)->get();

        foreach ($participants as $participant) {
            $user = $participant->user;

            $provinceName = $provinces[$user->province]['name'] ?? '';
            $regencyName  = $regencies[$user->city]['name'] ?? '';
            $districtName = $districts[$user->subdistrict]['name'] ?? '';
            $villageName  = $villages[$user->village]['name'] ?? '';

            $addressParts = array_filter([$provinceName, $regencyName, $districtName, $villageName]);
            $address = implode(', ', $addressParts);

            $asalPengguna = $user->category_user === 'Internal Kampus'
                ? 'Mahasiswa J' . ($user->jurusan->kode_jurusan ?? '')
                : 'Eksternal Kampus';

            $data->push([
                $i++,
                $participant->ticket_code,
                $user->name,
                $asalPengguna,
                $address
            ]);
        }


        return $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = count($this->header) + 64; // Hitung jumlah kolom dan konversi ke huruf ASCII (A=65, B=66, dst.)
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

                $event->sheet->setAutoFilter('D1');

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('A1:E1')->getFont()->setBold(true);

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
