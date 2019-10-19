<?php

   //require '/path/to/vendor/autoload.php';
require_once(__DIR__ . "/../../../../../../vendor/autoload.php");

class RemoteStorage {

    protected $s3;

    public function __construct() {

        $config = array(
            "region" => "us-east-2",
            "key" => "AKIA6HR35GUCRI3ZQLKR",
            "secret" => "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",
            "bucket" => "bbbs-document-storage-bucket"
        );


        $this->s3 = new Aws\S3\S3Client([
            'region'  => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret']
            ]
        ]);


    }

    public function transfer($localFile, $prefix = null) {

        try {
            $filename = basename($localFile);

            $key = ($prefix !== null) ? "uploads/" . $prefix . "/" . $filename : "uploads/" . $filename;

            $result = $this->s3->putObject([
                'Bucket' => 'bbbs-document-storage-bucket',
                'Key'    => $key,
                //'Body'   => 'this is the body!',
                'SourceFile' => $localFile
                //'SourceFile' => 'c:\samplefile.png' -- use this if you want to upload a file from a local location
            ]);

            $url = $result->get("ObjectURL");

            return $url;

        } catch (Exception $e) {
            return false;
        }
    }


    public function datePath() {
        $dt = new DateTime();
        return $dt->format("Y") . "/" . $dt->format("m") . "/" . $dt->format("d");
    }
}

/*
$rs = new RemoteStorage();
var_dump($rs->transfer("/Users/peteh/www/bbbs/testfile1.txt"));
var_dump($rs->transfer("/Users/peteh/www/bbbs/testfile1.txt","foo"));
var_dump($rs->transfer("/Users/peteh/www/bbbs/testfile1.txt",$rs->datePath()));
var_dump($rs->transfer("/Users/peteh/www/bbbs/testfile2.txt"));
*/