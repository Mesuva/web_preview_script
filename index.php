<?php
// config
$logopath  = ''; // use a url to an logo image, to place it in the top left.
$projecttitle = ''; // enter a value here to force a project title
$autonamefromfolder = true;  // if true and no project title is set, use parent folder name as project title
$autoremovenumericalprefix = true;  // change to false if you want to keep prefixes like '05 - ' on your titles.

$pathtoimages = 'images/';
$imagetypes = array('jpg', 'jpeg', 'png');

//Set no caching
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if ($autonamefromfolder && !$projecttitle) {
      $projecttitle = str_replace('_', ' ', basename(dirname($_SERVER['PHP_SELF']))); 
}

$images = glob($pathtoimages."*");
$allimages = ''; 
 
foreach($images as $image){
	$extension = pathinfo($image, PATHINFO_EXTENSION);
	
	if (in_array($extension, $imagetypes)){ 
		$handle = str_replace('_', ' ', preg_replace("/\\.[^.\\s]{3,4}$/", "",basename($image)));
		
        if ($autoremovenumericalprefix) {
            $handle = htmlspecialchars(trim(preg_replace("/^[0-9]*( |-)*/", "",$handle)));
        }
        
		$allimages[] = array(
			'filename'=>$image, 
			'handle'=> $handle
	    );
	}  
}

$request = '';

if (isset($_GET['i'])) { 
	$request = $_GET['i'];
}

$currentimage = '';
$nextimage = '';
$previousimage = '';
$note = '';

if (is_array($allimages)) {
	foreach($allimages as $key=>$image){
		 
		if ($request == $image['handle']) {
		 	$currentimage = $image['filename'];
		 	$currenthandle = $image['handle'];
		   	
		 	if (isset($allimages[$key+1])) {
		 		$nextimage = $allimages[$key+1]['handle'];
		 	}
		 	
		 	if ($key > 0) {
		 		$previousimage = $allimages[$key-1]['handle'];
		 	}
 	 	
		 	break 1;
		}
	}
}

// if we dont have a file request, or didn't end up with a file, start from the beginning
if ($request == '' || !$currentimage) {
	
	if (isset($allimages[0])) {
	   $currentimage = $allimages[0]['filename'];	
	   $currenthandle = $allimages[0]['handle'];
           
	} else {
		$currenthandle = 'no images found';	
	}
	
	if (isset($allimages[1])) {
		$nextimage = $allimages[1]['handle'];
	}
}
 
$currenttitle = $projecttitle . ($projecttitle ? ' - ' : '') . $currenthandle;

$note = '';

if ($currentimage) {
    $textfile = $handle = preg_replace("/\\.[^.\\s]{3,4}$/", "",basename($currentimage)). '.txt'; 
    
    if (file_exists($pathtoimages . $textfile)) {
        $note = file_get_contents($pathtoimages . $textfile);
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <link href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABm0lEQVR42mNkGGDAOOqAUQeMOmDIOcDFxcUMSCUAMYhWhArfB+JTQLxgz549p2jiAKDFlkBqGhAbEFB6AYizgA45ThUHAC3mBFJdQJxDYmBNB+JioEO+k+0AoOUSQGojAyS4kcFBIO4D4rNQvjEQFwGxPZo6UHT4Ax3xgmQHAC0XBVLHgFgFTaoDaGAlDj3tQKoCTfgOEFsB9bwm2gFAg/iB1GEg1sXic2egYX9x6GMGUgeA2AZN6jIQ2wL1fSToAGicH8AS7AxQQ45A1YGCuwsqXgYUP4gkfgCLXlB0OKCnCWwOmM8AyWbYgADMF0B1z4GUBFT8BVBcEin0PuDQD8qmiYQcsAhIxRLhgCdAShoq/hQoLgMV5wFSn3HoXwxUF0etKPAAUvOh4olA8R1UiQKoIUJQQ6iZCEGWv0PXgy8bguIXlBOokQ1tcZUFlBZEsHIfJE/dggjJEaA00QvEmYTUogHKi2I0h4DidTIDcZVRLiyxEgLkVMegWhGUTUHBDksfoHgGBfdiYmtBsh1AbTDqgFEHjDpgwB0AALcrpCEZQPxvAAAAAElFTkSuQmCC" rel="icon" type="image/x-icon" />

    <title>
        <?php echo $currenttitle; ?>
    </title>

    <style>
        <?php if ($currentimage) {
            $imagesize=getimagesize($currentimage);
            $height=$imagesize[1];
            ?> 
        .imageholder {
                background-position: center top;
                background-repeat: no-repeat;
                background-image: url('<?php echo $currentimage; ?>');
                height: <?php echo $height;
                ?>px;
                margin-top: 0;
            }
            <?php
        }
        ?> .logo {
            float: left;
            margin-top: 10px;
            margin-right: 40px;
            height: 30px;
        }
        .navbar {
            opacity: 0;
             top: -60px;
            transition: all .25s ease-in-out;
            -moz-transition: all .25s ease-in-out;
            -webkit-transition: all .25s ease-in-out;
        }
        .navbaractive {
            opacity: 0.9;
             top: 0;
        }
        .navbar:hover {
            opacity: 1;
            top: 0;
        }
        #noimagesnotice {
            margin-top: 60px;
            text-align: center;
        }
    </style>

<!--[if lte IE 8]>
<style>
.imageholder{
    margin-top:  50px;
}
</style>

  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->

</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top navbaractive" role="navigation">

        <div class="container">

            <div class="navbar-header">

                <?php if (isset($logopath) && $logopath) { ?>
                <img src="<?php echo $logopath; ?>" class="logo hidden-xs" alt="">
                <?php } ?>

                <span class="navbar-brand">

                    <?php if ($projecttitle) { ?>
                    <strong>
                        <?php echo $projecttitle; ?>
                    </strong>-
                    <?php } ?>

                    <?php echo $currenthandle; ?>
                </span>

            </div>


            <?php if (count($allimages)>1) { ?>
            <div class="navbar-collapse collapse">
                
                <ul class="nav navbar-nav navbar-right">
                    
                    <?php if ($note) { ?>
                     <li><a class="nolink" href="#" id="note" data-toggle="popover" data-placement="bottom" data-content="<?php echo htmlspecialchars($note); ?>" > <span class="glyphicon glyphicon-info-sign"></span></a></li>
                    <?php } ?>
                    
                    <?php if ($previousimage) { ?>
                    <li>
                        <a id="prev" href="?i=<?php echo urlencode($previousimage);?>">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="disabled">
                        <a href="#" class="nolink">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($nextimage) { ?>
                    <li>
                        <a id="next" href="?i=<?php echo urlencode($nextimage);?>">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="disabled">
                        <a href="#" class="nolink">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </li>
                    <?php } ?>

                    <?php if (!empty($allimages)) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-list"></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach($allimages as $image) { if ($image['handle']==$currenthandle) { ?>
                            <li class="disabled">
                                <a href="#">
                                    <?php echo $image[ 'handle']; ?>
                                </a>
                            </li>
                            <?php } else { ?>
                            <li>
                                <a href="?i=<?php echo urlencode($image['handle']); ?>">
                                    <?php echo $image[ 'handle']; ?>
                                </a>
                            </li>
                            <?php } ?>

                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                </ul>
            </div>

            <?php } ?>
        </div>

    </nav>

    <?php if ($currentimage) { if (count($allimages)>1) { ?>
    <a href="?i=<?php echo urlencode($nextimage); ?>">
        <?php } ?>

        <div class="imageholder">

        </div>
        <?php if (count($allimages)>0) { ?>
    </a>
    <?php } } else { ?>
    <div class="container" id="noimagesnotice">

        <h1>No Images Found</h1>
        <div class="alert alert-info">
            <p>Place images into an
                <span class="label label-default">images</span> folder in the same directory as this script</p>
            <p>Numerical filename prefixes can be used to sequence images, e.g.
                <span class="label label-default">02 - myfile.png</span>
            </p>
        </div>
    </div>

    <?php } ?>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('.navbaractive').removeClass('navbaractive');
            }, 2000);

            var timeset = false;

            $("body").mousemove(function() {
                $('.navbar').addClass('navbaractive');
                clearTimeout(timeset)
                timeset = setTimeout(function() {
                    $('.navbaractive').removeClass('navbaractive');
                }, 2000);
            });


            $(document).keydown(function(e) {
                if (e.keyCode == 37) { // left
                    if ($('#prev').length) {
                        window.location.href = $('#prev').attr('href');
                    }
                } else if (e.keyCode == 39) { // right
                    if ($('#next').length) {
                        window.location.href = $('#next').attr('href');
                    }
                }
            });
            
            $("#note").popover({container: 'body'});

            $('.nolink').click(function(){
                return false;
            });
                
        });
    </script>

</body>
</html>