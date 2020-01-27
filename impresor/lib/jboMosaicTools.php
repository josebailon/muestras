<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jboMosaicTools
 *
 * @author Jose
 */
class jboMosaicTools {
    
    /**
     * recoger mosaicos de base de datos
     * @return type
     */
    public static function getDbImageMosaics(){
        $imm=new jboImageMosaicModel();
        $mlist = $imm->getAll();
        return $mlist;
        
    }

    
    
    /**
     * recoger mosaicos para un formato
     * @param type $formatCode codigo de formato
     */
    public static function getAllMosaicsWithFormat($formatCode){
        $out=[];
        //recogemos el 101
        $out[101]=self::getMosaicWithFormat("101", $formatCode);
        
        $dbm=self::getDbImageMosaics();
        foreach ($dbm as $m){
            $out[$m->code]=self::getMosaicWithFormat($m->code, $formatCode);
        }
        
        return $out;
    }
    
    /**
     * recoger todos los formatos con todos los mosaicos
     */
    public static function getAllMosaicsForAllFormats(){
        $fm=new jboPaperFormatsModel();
        $out=[];
        $formats= $fm->getAll();
        foreach ($formats as $f){
            $out[$f->code]= self::getAllMosaicsWithFormat($f->code);
        }
        return $out;
    }
    /**
     * recogerm mosaico para un formato y un codigo de mosaico
     * @param type $mosaicCode
     * @param type $formatCode
     */
    public static function getMosaicWithFormat($mosaicCode,$formatCode){
        //recogemos el formato
        $fm = new jboPaperFormatsModel();
        $format = $fm->getBycode($formatCode);
        
        //si es 101 inicializamos la salida y la devolvemos
        if ($mosaicCode==101){
            $out = new jboImageMosaic(101, "Pág Completa", jboTools::pt2cm($format->w), jboTools::pt2cm($format->h), 0);
            $out->col=1;
            $out->row=1;
            $out->angle=false;
            $out->total=1;
            return $out;
        }     
        
        //como no es 101 hacemos todos los calculos
        $imm= new jboImageMosaicModel();
        $mdata = $imm->getByCode($mosaicCode);
                
        //calculo del area imprimible de un formato cogiendo su tamanio y reduciendo el margen
        //ancho del formato menos el margen x 2
        $fw = bcsub(bcsub(jboTools::pt2cm($format->w), jboTools::pt2cm(jboConf::$margin), 2), jboTools::pt2cm(jboConf::$margin), 2); 
        //alto del formato menos el margen x 2
        $fh = bcsub(bcsub(jboTools::pt2cm($format->h), jboTools::pt2cm(jboConf::$margin), 2), jboTools::pt2cm(jboConf::$margin), 2); 
        
        //CALCULOS PARA LA CUADRICULA
             //CALCULO COLUMNAS FILAS
                //calcular copias que caben por pliego
                $pagesPerSheet=1;
                
                //calcular cuantas caben poniendola en LANDSCAPE
                //#######################################################################
                $wLCount=0;//columnas landscape
                $hLCount=0;//filas landscape
                $wTotal=0.0;//ancho alcanzado
                $hTotal=0.0;//alto alcanzado
                $first=true;//si es el primer intento
                $totalDrawSapceWL=$fw;//espacio total usado por imagen(sin margenes para calcular el tamano de la celda posteriormente)
                $totalDrawSapceHL=$fh;//espacio total usado por imagen(sin margenes para calcular el tamano de la celda posteriormente)
                //cuenta horizontal
                while($wTotal<$fw){
                    if (!$first){
                        $wTotal=bcadd($wTotal, floatval($mdata->delta),2);//add delta si no es la primera
                        $totalDrawSapceWL= bcsub($totalDrawSapceWL, floatval($mdata->delta),2);//restar delta para calcular espacio final
                    }
                    $wTotal= bcadd( $wTotal,$mdata->h,2);//add imagen
                    $first=false;//ya no es el primero
                    if ($wTotal>$fw){//si nos hemos colado paramos
                        break;
                    }
                    $wLCount+=1;//si hemos llegado aqui es que se cuenta la imagen
                }
                //cuenta vertical
                $first=true;
                while($hTotal<$fh){
                    if (!$first){
                        $hTotal=bcadd($hTotal,$mdata->delta,2);//add delta si no es la primer
                        $totalDrawSapceHL= bcsub($totalDrawSapceHL, floatval($mdata->delta),2);//restar delta para calcular espacio final
                    }//add delta si no es la primera
                    $hTotal= bcadd( $hTotal,$mdata->w,2);//add imagen
                    $first=false;//ya no es el primero
                    if ($hTotal>$fh){//si nos hemos colado paramos
                        break;
                    }
                    $hLCount+=1;//si hemos llegado aqui es que se cuenta la imagen
                }
                 $landscapeTotal=$wLCount*$hLCount;
                

                //calcular cuantas caben poniendola en PORTRAIT 
                //######################################################################## 
                $wPCount=0;//columnas portrait
                $hPCount=0;//filas portrait
                $wTotal=0.0;//ancho alcanzado
                $hTotal=0.0;//alto alcanzado
                $first=true;//si es el primer intento
                $totalDrawSapceWP=$fw;//espacio total usado por imagen(sin margenes para calcular el tamano de la celda posteriormente)
                $totalDrawSapceHP=$fh;    //espacio total usado por imagen(sin margenes para calcular el tamano de la celda posteriormente)           
                //cuenta horizontal
                while($wTotal<$fw){
                    if (!$first){
                        $wTotal=bcadd($wTotal,$mdata->delta,2);//add delta si no es la primer
                        $totalDrawSapceWP= bcsub($totalDrawSapceWP, floatval($mdata->delta),2);//restar delta para calcular espacio final
                    }
                    $wTotal= bcadd( $wTotal,$mdata->w,2);//add imagen
                    $first=false;//ya no es el primero
                    if ($wTotal>$fw){//si nos hemos colado paramos
                        break;
                    }
                    $wPCount+=1;//si hemos llegado aqui es que se cuenta la imagen
                }
                 //cuenta vertical
                $first=true;
                while($hTotal<$fh){
                    if (!$first){
                        $hTotal=bcadd($hTotal,$mdata->delta,2);//add delta si no es la primera
                        $totalDrawSapceHP= bcsub($totalDrawSapceHP, floatval($mdata->delta),2);//restar delta para calcular espacio final
                    }
                    $hTotal= bcadd( $hTotal,$mdata->h,2);//add imagen
                    $first=false;//ya no es el primero
                    if ($hTotal>$fh){//si nos hemos colado paramos
                        break;
                    }
                    $hPCount+=1;//si hemos llegado aqui es que se cuenta la imagen

                }
                 $portraitTotal=$wPCount*$hPCount;
                 
             
                //quedarnos con la mas grande y sus columnas y filas

                if ($portraitTotal>$landscapeTotal){
                    $pagesPerSheet=$portraitTotal;
                    $col=$wPCount;
                    $row=$hPCount;
                    $drawSpaceW= bcdiv($totalDrawSapceWP,$col,2);//espacio  usado por una celda imagen(sin margenes para calcular el tamano de la celda posteriormente)
                    $drawSpaceH= bcdiv($totalDrawSapceHP,$row,2);//espacio  usado por una celda imagen(sin margenes para calcular el tamano de la celda posteriormente)
                }else{
                    $pagesPerSheet=$landscapeTotal;
                    $col=$wLCount;
                    $row=$hLCount;
                    $drawSpaceW= bcdiv($totalDrawSapceWL,$col,2);//espacio  usado por una celda imagen(sin margenes para calcular el tamano de la celda posteriormente)
                    $drawSpaceH= bcdiv($totalDrawSapceHL,$row,2);//espacio  usado por una celda imagen(sin margenes para calcular el tamano de la celda posteriormente)
                }
                
                //FIN CALCUO COLUMNAS FILAS
        
                $angle=false;
                if (jboTools::isLandscape($drawSpaceW, $drawSpaceH)){
                    $angle=90;
                }
                
            $mdata->col=$col;
            $mdata->row=$row;
            $mdata->angle=$angle;
            $mdata->total=$pagesPerSheet;
                
        return $mdata;
    }//fin getMosaicWithFormat
    

    

}
