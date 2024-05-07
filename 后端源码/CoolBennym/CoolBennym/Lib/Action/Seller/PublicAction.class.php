<?php
class PublicAction extends CommonAction{
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(4,2,'png', 60, 30);
    }
  
}