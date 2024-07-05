<?php
namespace App\Models;

use \CodeIgniter\Model;

class Media extends Model {

    public function store_image($image, $folder='others') {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_files');
        helper(['form', 'url']);
        $image_mimes = ['image/jpg','image/jpeg','image/gif','image/png','image/webp'];

        $validated = false;

        if(in_array($image->getMimeType(),$image_mimes)) {
            $validated = true;
        }

        if ($validated) {
            $image->move(IMAGEUPLOADPATH.DIRECTORY_SEPARATOR.$folder);
            $data = [
                'name' =>  rand().'_'.$image->getClientName(),
                'path' =>  $folder.'/'.$image->getClientName(),
                'type'  => $image->getClientMimeType(),
                'size' => $image->getSize()
            ];

            if($builder->insert($data)) {
                $data['id'] = $builder->db()->insertID();
                return [
                  'success'=>true,
                  'data' => $data
                ];
            }else {
                return [
                    'success'=>false,
                    'data' => []
                ];
            }

        }else {
            return [
                'success'=>false,
                'data' => []
            ];
        }
    }

    public function get_file($media_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_files');
        $builder->select('*');

        return $builder->getWhere(['id'=>$media_id])->getFirstRow();
    }

    public function get_media_src($media_id=0,$return_type='',$resolution='full') {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_files');
        $builder->select('path');

        $row = $builder->getWhere(['id'=>$media_id])->getFirstRow();

        if(!empty($row) && $row->path) {
            switch ($return_type) {
                case 'filepath': $return = '/assets/images/site-images/'.$row->path; break;
                case 'basepath': $return = getcwd().'/assets/images/site-images/'.$row->path; break;
                default: $return = base_url().'/assets/images/site-images/'.$row->path; break;
            }
        }else {
            $return = base_url().'/assets/images/placeholder.jpg';
        }

        switch ($resolution) {
            case 'thumbnail': $resolution='300x300'; break;
            case 'medium': $resolution='800x800'; break;
            case 'large': $resolution='1024x768'; break;
        }
        $resolution_ = explode('x',$resolution);

        $image = $resolution == 'full' ? $return : base_url('/public/res.php?src='.$return.'&w='.$resolution_[0].'&h='.$resolution_[1]);

//        $imageData = base64_encode(file_get_contents($image));
//        $mime = mime_content_type($image);
//        if(!$mime) {
//            $mime = 'image/jpg';
//        }

        //$image = 'data: '.$mime.';base64,'.$imageData;

        return $image;
    }

}