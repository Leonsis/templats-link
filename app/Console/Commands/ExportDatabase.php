<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export database structure and data to SQL file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Exportando estrutura e dados do banco...');
        
        $sql = "-- MySQL dump gerado automaticamente pelo Laravel\n";
        $sql .= "-- Data: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: templats_link\n\n";
        
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";
        
        // Exportar tabelas
        $tables = ['head_configs', 'leads', 'rotas_dinamicas', 'personal_access_tokens'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("📋 Exportando tabela: {$table}");
                $sql .= $this->exportTable($table);
            }
        }
        
        $sql .= "COMMIT;\n";
        
        // Salvar arquivo
        $filename = 'database/templats_link_updated.sql';
        file_put_contents($filename, $sql);
        
        $this->info("✅ Banco exportado com sucesso para: {$filename}");
        $this->info("📊 Tamanho do arquivo: " . number_format(filesize($filename)) . " bytes");
    }
    
    private function exportTable($tableName)
    {
        $sql = "\n--\n-- Table structure for table `{$tableName}`\n--\n\n";
        
        // Estrutura da tabela
        $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
        $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
        $sql .= $createTable->{'Create Table'} . ";\n\n";
        
        // Dados da tabela
        $sql .= "--\n-- Dumping data for table `{$tableName}`\n--\n\n";
        
        $data = DB::table($tableName)->get();
        
        if ($data->count() > 0) {
            $sql .= "LOCK TABLES `{$tableName}` WRITE;\n";
            $sql .= "/*!40000 ALTER TABLE `{$tableName}` DISABLE KEYS */;\n";
            
            foreach ($data as $row) {
                $values = [];
                foreach ((array)$row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }
                $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(',', $values) . ");\n";
            }
            
            $sql .= "/*!40000 ALTER TABLE `{$tableName}` ENABLE KEYS */;\n";
            $sql .= "UNLOCK TABLES;\n";
        }
        
        return $sql;
    }
}
