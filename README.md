Client php test
======================

mini-project menggunakan untuk MySQL menggunakan PHP sebagai klien dangen Node.js sebagai contoh server 

ini merupakan perintah dan pengembangan sederhana mengenal pemetaan transaksi dan perpindahan skema database klien dan server

How to use
======================


Test penggunaan dan contoh klien script 

    git clone https://github.com/rasyidkaromi/php-klien

    setup config.php
    -- setting database
    -- setting diplay log debug for development
    -- setting Curl
    -- setting Loop init

Migrasi

    php migration.php
    atau 
    php main.php CreateTable 

Optional generate database dan table. Lihat (config.php)

    php main.php CreateDB
    -- generate database baru

    php main.php CreateTable
    -- migration table
    
    php main.php insertTableJSON
    -- menambahkan sample row data kedalam database. lihat (main.php) optional




Server
======================

Development server test

    -- setting MySQL (server.js)
    -- sample transactionflipclient.sql dan transactionflipserver.sql sebagai contoh 

Migrasi

    node BuatDataase.js
    node BuatTable.js

Jalankan server

    node server.js

    

Client disburse
======================

    php main.php postFlipApi
    -- pengiriman data transaksi ke server (lihat main.php)

    php main.php getMultipleFlipApi
    -- akses pengecekan status 'PENDING' transaksi id dari database klien dan dikirim ke server.
    -- menerima perubahan jika status 'SUCCESS' dan menyimpan kedalam database klien.

    php main.php getMultipleFlipApiLoop
    -- pengecekan Looping satus "PENDING" transaksi id dari database klien ke server 
    -- dan menerima perubahan jika status 'SUCCESS' dan menyimpan kedalam database klien 
    -- lihat define('LOOPTIME', 30 ) dan define('LOOPENABLE', true ) di config.php untuk mengatur loop



Development
======================

    php main.php isIDexist
    -- result cek jika transaksi id ada. Lihat (main.php)
    
    php main.php isTableexist
    -- result cek jika table ada
    
    php main.php disbursementStatusDB
    -- debug result Array transaksi id yang masih status 'PENDING'
    
    php main.php generateTrans
    -- generate 100 'transaction' id trasaksi dalam server secara random. Lihat (main.php dan server.js)


