<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use Media;

class MediaController extends BaseController
{
    private $master;
    private $data;

    public function __construct()
    {
        $this->master = model('MasterModel');
    }
    public function index()
    {
        $start = !empty($_GET['page']) ? $_GET['page'] : 1;
        $page = !empty($_GET['page']) ? $_GET['page'] : 'media-library';
        $limit = 15;
        if($start && $page > 1) {
            $end = ($limit*$start);
            $start = (($start-1)*$limit);
            $limitQuery = implode(',',[$end,$limit]);
            $limitQuery = trim($limitQuery,',');
        }else {
            $limitQuery = $limit;
        }

        $query = "SELECT * FROM tbl_files";

        if(!empty($_GET['q'])) {
            $str = $_GET['q'];
            $query .= " WHERE name LIKE '%$str%'";
        }

        $query .= " ORDER BY id DESC";

        $query .= " LIMIT $limitQuery";

        $media_files = $this->master->query($query);
        $query = "SELECT COUNT(*) AS count FROM tbl_files";

        if(!empty($_GET['q'])) {
            $str = $_GET['q'];
            $query .= " WHERE name LIKE '%$str%'";
        }



        $fileCount = $this->master->query($query);
        $fileCount = $fileCount[0]->count;

        $this->data['content'] = 'admin/media-library';

        $this->data = array_merge($this->data,['media_files'=>$media_files,'page'=>'media-library','filecount'=>$fileCount,'limit'=>$limit,'page'=>$page]);


        _render_page('/' . ADMIN . '/index', $this->data);

       //return view('admin/media-library',['media_files'=>$media_files,'page'=>'media-library','filecount'=>$fileCount,'limit'=>$limit,'page'=>$page]);
    }

    public function media_gallery_frame() {

        $start = !empty($_GET['page']) ? $_GET['page'] : 1;
        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $limit = 18;
        if($start && $page > 1) {
            $end = ($limit*$start);
            $start = (($start-1)*$limit);
            $limitQuery = implode(',',[$end,$limit]);
            $limitQuery = trim($limitQuery,',');
        }else {
            $limitQuery = $limit;
        }

        $query = "SELECT * FROM tbl_files";

        if(!empty($_GET['q'])) {
            $str = $_GET['q'];
            $query .= " WHERE name LIKE '%$str%'";
        }

        $query .= " ORDER BY created_at DESC";

        $query .= " LIMIT $limitQuery";

        $media_files = $this->master->query($query);

        $query = "SELECT COUNT(*) AS count FROM tbl_files";

        if(!empty($_GET['q'])) {
            $str = $_GET['q'];
            $query .= " WHERE name LIKE '%$str%'";
        }

        $selectedMedias = !empty($_GET['selected']) ? $_GET['selected'] : '';
        $fileCount = $this->master->query($query);
        $fileCount = $fileCount[0]->count;

        return view('admin/includes/media-library-viewer',['selected_medias'=>$selectedMedias,'media_files'=>$media_files,'page'=>'media-library','filecount'=>$fileCount,'limit'=>$limit,'page'=>$page]);
    }

    public function media_path_by_id($id) {
        if($id) {
            $media = model('Media');
            echo $media->get_media_src($id);
            exit;
        }
    }
    // public function media_upload() {
    //     $files = $this->request->getFiles();
    //     if(!empty($files['upload_files'])) {
    //         $files = $files['upload_files'];
    //         $media = model('Media');
    //         $success = true; // Assume success initially
    
    //         foreach($files as $file) {
    //             $uploads = $media->store_image($file, 'products');
    //             if(empty($uploads['success'])) {
    //                 $success = false;
    //                 break; // If any upload fails, break out and set success to false
    //             }
    //         }
    
    //         if($success) {
    //             echo json_encode(['success' => 1, 'message' => 'Files uploaded successfully']);
    //         } else {
    //             echo json_encode(['success' => 0, 'message' => 'Failed to upload some file(s)']);
    //         }
    
    //         exit; // Exit to prevent further output
    //     }
    // }

    public function media_upload() {
        $files = $this->request->getFiles();
        if(!empty($files['upload_files'])) {
            $files = $files['upload_files'];
            $media = model('Media');
            $maxFileSize = 4 * 1024 * 1024; // 4MB in bytes
            $success = true; // Assume success initially
            $errorMessages = [];
    
            foreach($files as $file) {
                // Check if file size exceeds 4MB
                if ($file->getSize() > $maxFileSize) {
                    $success = false;
                    $errorMessages[] = 'Recommended 4mb max image upload size
                    ';
                    break; // Break if any file exceeds size limit
                }
    
                // Check if image is 72 DPI
                $imageInfo = getimagesize($file->getTempName());
                if ($imageInfo && isset($imageInfo['resolution']) && ($imageInfo['resolution'][0] != 72 || $imageInfo['resolution'][1] != 72)) {
                    $success = false;
                    $errorMessages[] = 'Image resolution should be 72 DPI.';
                    break; // Break if image resolution is not 72 DPI
                }
    
                // Proceed with file upload
                $uploads = $media->store_image($file, 'products');
                if(empty($uploads['success'])) {
                    $success = false;
                    $errorMessages[] = 'Failed to upload some file(s).';
                    break; // If any upload fails, break out and set success to false
                }
            }
    
            if($success) {
                echo json_encode(['success' => 1, 'message' => 'Files uploaded successfully']);
            } else {
                echo json_encode(['success' => 0, 'message' => implode(', ', $errorMessages)]);
            }
    
            exit; // Exit to prevent further output
        }
    }
    
    

    public function delete_media($del_id=0) {
        if(!empty($del_id)) {
            $media = model('Media');
            $getMedia = $media->get_media_src($del_id,'basepath');
            $this->master->delete_data('tbl_files','id',$del_id);
            if(file_exists($getMedia)) {
                unlink($getMedia);
            }
            if(!is_ajax()) {
                notice_success('Media deleted.');
                return redirect()->to($_SERVER['HTTP_REFERER']);
            }else {
                echo json_encode(['msg'=>'Media deleted','success'=>1]);
            }
        }
        else {
            echo json_encode(['msg'=>'Could not delete media','success'=>0]);
        }
    }
}
