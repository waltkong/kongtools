<?php
namespace KongTools\Utils\ImageServer\Qcloud;
use KongTools\Exceptions\UploadException;
use Qcloud\Cos\Client;
/**
 * 腾讯云图片服务器
 */
class ImageServer {

    const PKG_VERSION = '2.0.1';

    const API_IMAGE_END_POINT = 'http://web.image.myqcloud.com/photos/v1/';

    const API_IMAGE_END_POINT_V2 = 'http://web.image.myqcloud.com/photos/v2/';

    const API_VIDEO_END_POINT = 'http://web.video.myqcloud.com/videos/v1/';

    const API_PRONDETECT_URL = 'http://service.image.myqcloud.com/detection/pornDetect';

    private $cosClient;
    private $validSize = 1024*1024*5; //5M

    // 以下部分请您根据在qcloud申请到的项目id和对应的secret id和secret key进行修改
    private $APPID;   //10000000;
    private $SECRET_ID; //'AKaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    private $SECRET_KEY;  //'aaaaaaaaaaaaaaaaaaaaaaaaaa';
    private $COS_REGION;  //'ap-shanghai';
    private $SAVE_PATH;  //"http://testimage-10014871.image.myqcloud.com/";
    private $BUCKET;  //testimage


    public function __construct(array $options){

        $default_options = [
            'app_id' => '',
            'secret_id' => '',
            'secret_key' => '',
            'cos_region' => '',
            'save_path' => '',
            'bucket' => '',

            'valid_size' => 1024*1024*5, //5M
        ];
        foreach ($options as $k => $option){
            $default_options[$k] = $option;
        }

        $this->APPID = $default_options['app_id'];
        $this->SECRET_ID =  $default_options['secret_id'];
        $this->SECRET_KEY =  $default_options['secret_key'];
        $this->COS_REGION =  $default_options['cos_region'];
        $this->SAVE_PATH =  $default_options['save_path'];
        $this->BUCKET =  $default_options['bucket'];
        $this->validSize = $default_options['valid_size'];

        $this->cosClient = new Client([
            'region' => $this->COS_REGION,
            'credentials'=> array(
                'appId' => $this->APPID,
                'secretId'    => $this->SECRET_ID,
                'secretKey' => $this->SECRET_KEY,
            )
        ]);


    }


    /**
     *
     * @param $file mixed 拿到的 $_FILES['file']
     * @return mixed
     * @throws UploadException
     * @throws \Exception
     */
    private function checkFile($file){
        if($file['error'] != 0){
            throw new UploadException('文件上传出错');
        }
        if ($file['size'] > $this->validSize || $file['size'] = 0){
            throw new UploadException('文件大小超过');
        }
        return $file['tmp_name'];
    }


    /**
     *  上传文件流
     * @param $fileInfo mixed 拿到的 $_FILES['file']
     * @return mixed    $key => 图片服务器该图片唯一标识(既：文件名)
     */
    public function upload($fileInfo){   //拿到的 $_FILES['file']
        try {
            $fileTemp = $this->checkFile($fileInfo);
            $filestream = fopen($fileTemp, 'rb');
            $key = $this->generateKey();
            $result = $this->cosClient->putObject([
                    'Bucket' => $this->BUCKET,
                    'Key' => (string)$key,
                    'Body' => $filestream,    //fopen('./img.jpg', 'rb')
            ]);
            return [
                'file_name' => $key,
                'file_dir' => $this->SAVE_PATH,
                'full_name' => $this->SAVE_PATH.$key,
            ];

        } catch (\Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }

    /**
     *  根据 key（oss文件名）获取 路径
     * @param $key
     */
    public function getUrlWithKey($key){
        return $this->SAVE_PATH.$key;
    }


    /**
     *   # 下载文件
     * getObject(下载文件)
     * memory 下载到内存   file 下载到本地
     * @param $key
     * @return bool
     */
    public function download($key){
        try {
            $obj = [
                'Bucket' => $this->BUCKET,
                'Key' =>  (string)$key,
            ];
            $result = $this->cosClient->getObject($obj);
            return $result['Body'];  //图片流
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 生成文件名
     * @return string
     */
    private function generateKey(){
        $str =  date('YmdHis',time()).rand(10000000,99999999);
        return (string)$str;
    }






}
