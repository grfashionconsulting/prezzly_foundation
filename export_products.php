<?php
// Mostra tutti gli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Imposta il limite di memoria a un valore sicuro (sotto il limite del server)
ini_set('memory_limit', '1536M');  // 1.5GB - lascia un margine di sicurezza
set_time_limit(1200);           // Aumenta il timeout a 20 minuti

// Includi i file di configurazione necessari
if (!file_exists("config.php")) {
    die("config.php non trovato. Assicurati che il file esista nella directory corrente.");
}

require("includes/common.php");

// Directory per i file esportati
$outputDir = "exports";

// Base del nome file per le parti
$baseFilename = "products_export";

// Assicurati che la directory esista
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0755, true)) {
        die("Impossibile creare la directory $outputDir");
    }
}

// Aumenta il numero di parti per ridurre la dimensione di ciascun file
$numParts = 8;  // Aumentato a 8 parti

// Dimensione del batch molto ridotta per minimizzare l'utilizzo della memoria
$batchSize = 200;  // Ridotto a 200 prodotti per batch

try {
    // Prima determiniamo il numero totale di prodotti
    $countSql = "SELECT COUNT(*) AS total FROM `" . $config_databaseTablePrefix . "products`";
    if (!database_querySelect($countSql, $countResult)) {
        $connection = database_getConnection();
        $error = $connection ? mysqli_error($connection) : "Errore di connessione al database";
        die("Errore nella query di conteggio: $error");
    }
    
    $totalProducts = (int)$countResult[0]['total'];
    
    if ($totalProducts <= 0) {
        die("Nessun prodotto trovato nel database.");
    }
    
    echo "Trovati $totalProducts prodotti da esportare.<br>";
    
    // Calcoliamo quanti prodotti per ogni file
    $productsPerPart = (int)ceil($totalProducts / $numParts);
    
    // Otteniamo solo i nomi delle colonne senza caricare dati
    $headerSql = "SHOW COLUMNS FROM `" . $config_databaseTablePrefix . "products`";
    if (!database_querySelect($headerSql, $headerResult)) {
        $connection = database_getConnection();
        $error = $connection ? mysqli_error($connection) : "Errore di connessione al database";
        die("Errore nell'ottenere la struttura della tabella: $error");
    }
    
    if (empty($headerResult)) {
        die("Impossibile determinare la struttura della tabella prodotti.");
    }
    
    // Estrai i nomi delle colonne
    $headers = [];
    foreach ($headerResult as $column) {
        $headers[] = $column['Field'];
    }
    
    // Array per memorizzare gli URL dei file generati
    $fileUrls = [];
    
    // Genera l'URL base per i file
    $siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $scriptDir = dirname($_SERVER['PHP_SELF']);
    $baseUrl = $siteUrl . $scriptDir;
    
    // Elabora ogni parte separatamente
    for ($part = 1; $part <= $numParts; $part++) {
        // Calcola l'offset per questa parte
        $offset = ($part - 1) * $productsPerPart;
        
        // Nome del file per questa parte
        $outputFile = "$outputDir/{$baseFilename}_part$part.csv";
        
        // Apri il file CSV in scrittura con modalità esplicita
        $fp = fopen($outputFile, 'w');
        if (!$fp) {
            die("Impossibile aprire il file $outputFile in scrittura. Verifica i permessi della directory.");
        }
        
        // Scrivi l'intestazione del CSV
        fputcsv($fp, $headers);
        
        // Elabora questa parte in batch più piccoli
        $processed = 0;
        $partLimit = min($productsPerPart, $totalProducts - $offset);
        
        while ($processed < $partLimit) {
            // Calcola il limite per questo batch
            $limit = min($batchSize, $partLimit - $processed);
            $currentOffset = $offset + $processed;
            
            // Query per selezionare un batch di prodotti
            $sql = "SELECT * FROM `" . $config_databaseTablePrefix . "products` LIMIT " . (int)$currentOffset . ", " . (int)$limit;
            
            $products = [];
            if (!database_querySelect($sql, $products)) {
                $connection = database_getConnection();
                $error = $connection ? mysqli_error($connection) : "Errore di connessione al database";
                die("Errore nella query batch: $error");
            }
            
            // Scrivi ogni prodotto nel CSV
            foreach ($products as $product) {
                // Assicurati che tutti i valori siano stringhe o tipi compatibili
                $productValues = array_map(function($value) {
                    return $value === null ? '' : (string)$value;
                }, $product);
                
                fputcsv($fp, $productValues);
            }
            
            // Aggiorna il contatore
            $processed += count($products);
            
            // Libera immediatamente la memoria
            unset($products);
            
            // Forza la garbage collection
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
            
            // Aggiorna lo stato
            echo "Parte $part: Elaborati $processed/$partLimit prodotti.<br>";
            
            // Svuota l'output buffer per mostrare l'avanzamento
            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
        }
        
        // Chiudi il file
        fclose($fp);
        
        // Aggiungi l'URL di questo file all'array
        $fileUrls[$part - 1] = $baseUrl . "/" . $outputFile;
        
        echo "Completata esportazione parte $part.<br>";
    }
    
    // Crea un file di indice che elenca tutti i file generati
    $indexFile = "$outputDir/{$baseFilename}_index.txt";
    $indexContent = "Esportazione prodotti completata il " . date('Y-m-d H:i:s') . "\n";
    $indexContent .= "Totale prodotti: $totalProducts\n";
    $indexContent .= "Suddivisi in $numParts file:\n\n";
    
    for ($i = 0; $i < $numParts; $i++) {
        $indexContent .= "File " . ($i + 1) . ": " . $fileUrls[$i] . "\n";
    }
    
    file_put_contents($indexFile, $indexContent);
    
    // Output delle informazioni
    echo "<br>Esportazione completata con successo.<br>";
    echo "Totale prodotti esportati: $totalProducts<br>";
    echo "Suddivisi in $numParts file:<br><br>";
    
    for ($i = 0; $i < $numParts; $i++) {
        echo "File " . ($i + 1) . ": <a href='" . $fileUrls[$i] . "'>" . $fileUrls[$i] . "</a><br>";
    }
    
    // Log dell'esportazione
    $logFile = "$outputDir/export_log.txt";
    file_put_contents(
        $logFile, 
        date('Y-m-d H:i:s') . " - Esportati $totalProducts prodotti in $numParts file\n", 
        FILE_APPEND
    );
    
} catch (Throwable $e) {
    // In PHP 8.4, è meglio usare Throwable che copre sia Exception che Error
    die("Si è verificato un errore: " . $e->getMessage() . " in " . $e->getFile() . " linea " . $e->getLine());
}
?>