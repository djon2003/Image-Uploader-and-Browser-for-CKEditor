<?php
// Including the plugin init file, don't delete the following row!

function loadImages() {
	global $useruploadpath, $useruploadfolder, $userUploadSiteRoot, $rootFolder, 
		   $file_style, $file_extens, $acceptedExtensions, 
		   $alerts9, $alerts10;
	
    if(file_exists($useruploadpath)){
        
        $count = 0;
        $calcsize = 0;
        
        $dir = $useruploadpath;
        $files = glob("$dir*.{" . join(',', $acceptedExtensions) . "}", GLOB_BRACE);
        usort($files, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
        for($i=count($files)-1; $i >= 0; $i--):
            $image = $files[$i];
            $image_pathinfo = pathinfo($image);
            $image_extension = $image_pathinfo['extension'];
            $image_filename = $image_pathinfo['filename'];
            $image_basename = $image_pathinfo['basename'];
        
            // image src/url
            $image_url = substr($image, strlen($rootFolder) + 1);
            $image_url = "$userUploadSiteRoot/$image_url";
            
            $size = @getimagesize($image);
           	$image_height = $size[0];
            if ($image_height == null)
            	$image_height = 0; //Not able to get info
           		
            $file_size_byte = filesize($image);
            $calcsize += $file_size_byte;
            $count = ++$count;
            
            if($file_style == "block") { ?>
                <div class="fileDiv"
                     onclick="showEditBar('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $count; ?>','<?php echo $image_basename; ?>');"
                     ondblclick="showImage('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $image_basename; ?>');"
                     data-imgid="<?php echo $count; ?>">
                    <div class="imgDiv"><img class="fileImg lazy" data-original="<?php echo $image_url; ?>"></div>
                    <p class="fileDescription"><span class="fileMime"><?php echo $image_extension; ?></span> <?php echo $image_filename; ?><?php if($file_extens == "yes"){echo ".$image_extension";} ?></p>
                    <p class="fileTime"><?php echo date ("F d Y H:i", filemtime($image)); ?></p>
                    <p class="fileTime"><?=convertFileSize($file_size_byte)?></p>
                </div>
            <?php } elseif($file_style == "list") { ?>
                <div class="fullWidthFileDiv"
                     onclick="showEditBar('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $count; ?>','<?php echo $image_basename; ?>');"
                     ondblclick="showImage('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $image_basename; ?>');"
                     data-imgid="<?php echo $count; ?>">
                    <div class="fullWidthimgDiv"><img class="fullWidthfileImg lazy" data-original="<?php echo $image_url; ?>"></div>
                    <p class="fullWidthfileDescription"><?php echo $image_filename; ?><?php if($file_extens == "yes"){echo ".$image_extension";} ?></p>
                    
                    <div class="qEditIconsDiv">
                        <img title="Delete File" src="img/cd-icon-qtrash.png" class="qEditIconsImg" onclick="window.location.href = 'imgdelete.php?img=<?php echo $image_basename; ?>'">
                    </div>
                    
                    <p class="fullWidthfileTime fullWidthfileMime fullWidthlastChild"><?php echo $image_extension; ?></p>
                    <p class="fullWidthfileTime"><?=convertFileSize($file_size_byte)?></p>
                    <p class="fullWidthfileTime fullWidth30percent"><?php echo date ("F d Y H:i", filemtime($image)); ?></p>
                </div>
            <?php }

        endfor;
        if($count == 0){
            echo "<div class='fileDiv' style='display:none;'></div>";
            $calcsize = 0;
        }
        
        $finalFileSize = convertFileSize($calcsize);
        
        echo "
        <script>
            $( '#finalsize' ).html('$finalFileSize');
            $( '#finalcount' ).html('$count');
        </script>
        ";
    } else {
        echo '<div id="folderError">'.$alerts9.' <b>'.$useruploadfolder.'</b> '.$alerts10 . '</div>';
    } 
}

function pathHistory() {
	global $foldershistory;
	
    $latestpathes = array_slice($foldershistory, -3);
    $latestpathes = array_reverse($latestpathes);
    foreach($latestpathes as $folder) {
        echo '<p class="pathHistory" onclick="useHistoryPath(\''.$folder.'\');">'.$folder.'</p>';
    }
}

//FROM : http://php.net/manual/fr/function.filesize.php#112996
function convertFileSize($bytes)
{
	global $fileUnits;
	$bytes = floatval($bytes);
	$arBytes = array(
			0 => array(
					"UNIT" => $fileUnits["TB"],
					"VALUE" => pow(1024, 4)
			),
			1 => array(
					"UNIT" => $fileUnits["GB"],
					"VALUE" => pow(1024, 3)
			),
			2 => array(
					"UNIT" => $fileUnits["MB"],
					"VALUE" => pow(1024, 2)
			),
			3 => array(
					"UNIT" => $fileUnits["KB"],
					"VALUE" => 1024
			),
			4 => array(
					"UNIT" => $fileUnits["B"],
					"VALUE" => 1
			),
	);

	foreach($arBytes as $arItem)
	{
		if($bytes >= $arItem["VALUE"])
		{
			$result = $bytes / $arItem["VALUE"];
			$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
			break;
		}
	}
	
	return (!isset($result) ? '0 ' . $arBytes[4]['UNIT'] : $result);
}