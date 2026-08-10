<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IkuWisatawanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('iku_wisatawan')->truncate();

        // Data Nusantara 2025 (ada isinya)
        DB::table('iku_wisatawan')->insert([
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'BANGGAI KEPULAUAN','januari'=>21771,'februari'=>19406,'maret'=>15495,'april'=>28192,'mei'=>17756,'juni'=>21090,'juli'=>18513,'agustus'=>17489,'september'=>15191,'oktober'=>15915,'november'=>17534,'desember'=>18460,'total'=>226812,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-12 23:47:21'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'BANGGAI','januari'=>61301,'februari'=>48246,'maret'=>49363,'april'=>73222,'mei'=>46684,'juni'=>54640,'juli'=>52570,'agustus'=>46245,'september'=>46434,'oktober'=>47815,'november'=>54720,'desember'=>65636,'total'=>646876,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 13:45:45'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'MOROWALI','januari'=>91890,'februari'=>88178,'maret'=>109055,'april'=>95522,'mei'=>80301,'juni'=>83223,'juli'=>86145,'agustus'=>86223,'september'=>88472,'oktober'=>92415,'november'=>91370,'desember'=>109037,'total'=>1101831,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'POSO','januari'=>98487,'februari'=>69251,'maret'=>68799,'april'=>94105,'mei'=>74591,'juni'=>88661,'juli'=>78844,'agustus'=>81796,'september'=>76010,'oktober'=>77224,'november'=>72371,'desember'=>88976,'total'=>969115,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'DONGGALA','januari'=>103198,'februari'=>86670,'maret'=>73096,'april'=>134054,'mei'=>93184,'juni'=>115526,'juli'=>89997,'agustus'=>92603,'september'=>91704,'oktober'=>94699,'november'=>100267,'desember'=>116307,'total'=>1191305,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'TOLI-TOLI','januari'=>28698,'februari'=>28214,'maret'=>24745,'april'=>51706,'mei'=>25820,'juni'=>33041,'juli'=>30341,'agustus'=>33722,'september'=>26431,'oktober'=>28123,'november'=>28082,'desember'=>35284,'total'=>374207,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'BUOL','januari'=>15554,'februari'=>14133,'maret'=>12399,'april'=>23426,'mei'=>12548,'juni'=>16462,'juli'=>14527,'agustus'=>30741,'september'=>14086,'oktober'=>14301,'november'=>14532,'desember'=>16979,'total'=>199688,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'PARIGI MOUTONG','januari'=>88046,'februari'=>80368,'maret'=>79904,'april'=>118965,'mei'=>71184,'juni'=>108449,'juli'=>82448,'agustus'=>83418,'september'=>74980,'oktober'=>80451,'november'=>86335,'desember'=>101486,'total'=>1056034,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'TOJO UNA-UNA','januari'=>30154,'februari'=>25670,'maret'=>22614,'april'=>43639,'mei'=>25196,'juni'=>34895,'juli'=>26982,'agustus'=>24618,'september'=>26216,'oktober'=>27610,'november'=>28536,'desember'=>34287,'total'=>350417,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'SIGI','januari'=>102865,'februari'=>95880,'maret'=>89741,'april'=>119836,'mei'=>102821,'juni'=>113177,'juli'=>104679,'agustus'=>96169,'september'=>102025,'oktober'=>106588,'november'=>108333,'desember'=>127960,'total'=>1270074,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'BANGGAI LAUT','januari'=>16601,'februari'=>14956,'maret'=>11805,'april'=>14988,'mei'=>10658,'juni'=>11590,'juli'=>10111,'agustus'=>9422,'september'=>10225,'oktober'=>11746,'november'=>18103,'desember'=>11505,'total'=>151710,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'MOROWALI UTARA','januari'=>55254,'februari'=>45937,'maret'=>51150,'april'=>56284,'mei'=>49759,'juni'=>55047,'juli'=>52776,'agustus'=>46957,'september'=>44765,'oktober'=>44587,'november'=>46235,'desember'=>55931,'total'=>604682,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
            ['kategori'=>'Wisatawan','subkategori'=>'Nusantara','tahun'=>'2025','kabkota'=>'KOTA PALU','januari'=>290944,'februari'=>258194,'maret'=>251599,'april'=>293976,'mei'=>303761,'juni'=>305960,'juli'=>303682,'agustus'=>276747,'september'=>278654,'oktober'=>300575,'november'=>306081,'desember'=>356009,'total'=>3526182,'created_at'=>'2026-07-12 15:27:04','updated_at'=>'2026-07-13 14:22:58'],
        ]);

        // Data Mancanegara 2025
        DB::table('iku_wisatawan')->insert([
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'BANGGAI KEPULAUAN','januari'=>61,'februari'=>82,'maret'=>123,'april'=>335,'mei'=>537,'juni'=>690,'juli'=>831,'agustus'=>1206,'september'=>968,'oktober'=>800,'november'=>509,'desember'=>278,'total'=>6420,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 00:43:02'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'BANGGAI','januari'=>78,'februari'=>175,'maret'=>225,'april'=>213,'mei'=>241,'juni'=>216,'juli'=>349,'agustus'=>230,'september'=>154,'oktober'=>180,'november'=>453,'desember'=>225,'total'=>2739,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'MOROWALI','januari'=>7,'februari'=>1,'maret'=>0,'april'=>14,'mei'=>13,'juni'=>3,'juli'=>38,'agustus'=>47,'september'=>32,'oktober'=>33,'november'=>8,'desember'=>11,'total'=>207,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'POSO','januari'=>83,'februari'=>43,'maret'=>51,'april'=>86,'mei'=>130,'juni'=>110,'juli'=>22,'agustus'=>391,'september'=>239,'oktober'=>182,'november'=>114,'desember'=>3,'total'=>1454,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'DONGGALA','januari'=>2,'februari'=>0,'maret'=>0,'april'=>13,'mei'=>7,'juni'=>38,'juli'=>8,'agustus'=>37,'september'=>4,'oktober'=>0,'november'=>4,'desember'=>12,'total'=>125,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'TOLI-TOLI','januari'=>0,'februari'=>1,'maret'=>1,'april'=>1,'mei'=>2,'juni'=>0,'juli'=>6,'agustus'=>1,'september'=>9,'oktober'=>0,'november'=>0,'desember'=>0,'total'=>21,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'BUOL','januari'=>0,'februari'=>0,'maret'=>0,'april'=>0,'mei'=>0,'juni'=>0,'juli'=>0,'agustus'=>5,'september'=>0,'oktober'=>0,'november'=>10,'desember'=>0,'total'=>15,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'PARIGI MOUTONG','januari'=>0,'februari'=>0,'maret'=>25,'april'=>0,'mei'=>0,'juni'=>0,'juli'=>0,'agustus'=>0,'september'=>0,'oktober'=>0,'november'=>15,'desember'=>0,'total'=>40,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'TOJO UNA-UNA','januari'=>195,'februari'=>353,'maret'=>346,'april'=>506,'mei'=>402,'juni'=>966,'juli'=>1467,'agustus'=>1804,'september'=>875,'oktober'=>1021,'november'=>348,'desember'=>313,'total'=>8596,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'SIGI','januari'=>0,'februari'=>0,'maret'=>0,'april'=>0,'mei'=>0,'juni'=>0,'juli'=>0,'agustus'=>0,'september'=>11,'oktober'=>0,'november'=>3,'desember'=>2,'total'=>16,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'BANGGAI LAUT','januari'=>66,'februari'=>65,'maret'=>75,'april'=>100,'mei'=>83,'juni'=>72,'juli'=>108,'agustus'=>179,'september'=>157,'oktober'=>155,'november'=>181,'desember'=>320,'total'=>1561,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'MOROWALI UTARA','januari'=>0,'februari'=>0,'maret'=>0,'april'=>0,'mei'=>0,'juni'=>0,'juli'=>2,'agustus'=>0,'september'=>4,'oktober'=>6,'november'=>4,'desember'=>0,'total'=>16,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
            ['kategori'=>'Wisatawan','subkategori'=>'Mancanegara','tahun'=>'2025','kabkota'=>'KOTA PALU','januari'=>497,'februari'=>848,'maret'=>883,'april'=>449,'mei'=>464,'juni'=>483,'juli'=>471,'agustus'=>407,'september'=>613,'oktober'=>994,'november'=>396,'desember'=>450,'total'=>6955,'created_at'=>'2026-07-12 15:27:11','updated_at'=>'2026-07-13 14:32:31'],
        ]);

        // Generate data kosong untuk tahun lain dan subkategori Akumulasi
        $kabkota_list = ['BANGGAI KEPULAUAN','BANGGAI','MOROWALI','POSO','DONGGALA','TOLI-TOLI','BUOL','PARIGI MOUTONG','TOJO UNA-UNA','SIGI','BANGGAI LAUT','MOROWALI UTARA','KOTA PALU'];
        $tahun_list = ['2026','2027','2028','2029','2030'];
        $subkategori_list = ['Nusantara','Mancanegara','Akumulasi'];
        $now = now()->toDateTimeString();

        foreach ($tahun_list as $tahun) {
            foreach ($subkategori_list as $sub) {
                foreach ($kabkota_list as $kab) {
                    DB::table('iku_wisatawan')->insert([
                        'kategori'=>'Wisatawan','subkategori'=>$sub,'tahun'=>$tahun,'kabkota'=>$kab,
                        'januari'=>0,'februari'=>0,'maret'=>0,'april'=>0,'mei'=>0,'juni'=>0,
                        'juli'=>0,'agustus'=>0,'september'=>0,'oktober'=>0,'november'=>0,'desember'=>0,
                        'total'=>0,'created_at'=>$now,'updated_at'=>$now,
                    ]);
                }
            }
        }

        // Akumulasi 2025
        foreach ($kabkota_list as $kab) {
            DB::table('iku_wisatawan')->insert([
                'kategori'=>'Wisatawan','subkategori'=>'Akumulasi','tahun'=>'2025','kabkota'=>$kab,
                'januari'=>0,'februari'=>0,'maret'=>0,'april'=>0,'mei'=>0,'juni'=>0,
                'juli'=>0,'agustus'=>0,'september'=>0,'oktober'=>0,'november'=>0,'desember'=>0,
                'total'=>0,'created_at'=>$now,'updated_at'=>$now,
            ]);
        }
    }
}
