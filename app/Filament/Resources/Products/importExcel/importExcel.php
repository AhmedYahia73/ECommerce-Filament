<?php 
namespace App\Filament\Resources\Products\importExcel;

use App\Models\Product; 
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importExcel implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Product::create([
                'name'               => $row['product_name'], 
                'description'        => $row['description'],  
                'price'              => $row['price'],        
                'category_id'        => $row['category_id'],  
                'unit_id'            => $row['unit_id'],      
                'short_description'  => $row['short_description'], 
                'offer_price'        => $row['offer_price'],
                'start_date'         => $row['start_date'],
                'end_date'           => $row['end_date'],
                'quantity'           => $row['quantity'],
                'quantity_at_packet' => $row['quantity_at_packet'],
            ]);
        }
    }
}