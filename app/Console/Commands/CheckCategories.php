<?php

namespace App\Console\Commands;

use App\Models\Product\Category;
use Illuminate\Console\Command;

class CheckCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check categories in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categories = Category::select('id', 'name', 'type')->get();
        
        $this->info('Total categories: ' . $categories->count());
        $this->info('Categories by type:');
        
        foreach ($categories as $category) {
            $this->line("ID: {$category->id} | Name: {$category->name} | Type: {$category->type}");
        }
        
        $barangCount = $categories->where('type', 'barang')->count();
        $jasaCount = $categories->where('type', 'jasa')->count();
        
        $this->info("Barang categories: {$barangCount}");
        $this->info("Jasa categories: {$jasaCount}");
    }
}
