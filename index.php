<html>
<head>
	<title>vkfeed</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="defaut.css">
</head>
<body>
<?php 

require 'config.php';
require 'ParseurRSS_class.php';

$parseur = new ParseurRSS();
$data=[];

function get_data($sources=[])
{
	GLOBAL $data, $parseur, $jour;

	foreach ($sources as $source) {
		$retourParser = $parseur->parser($source, 0, true);
		foreach ($retourParser as $value) {

			if( (strtotime($value['pubDate']) >= (time()-(3600*24)*$jour)) OR empty($value['pubDate']) )$data[] = $value;
		}
	}

	usort($data, "cmp");
}

function cmp($a, $b){
    return strtotime($b['pubDate'])-strtotime($a['pubDate']);
} 

ksort($flux);

$selection = $flux;
if(isset($_GET['flux']) AND isset($flux[$_GET['flux']]))$selection = [$flux[$_GET['flux']]];

get_data($selection);

?>

<nav>
	<ul>
	<li><a href="index.php">Tout les flux</a></li>		
	<?php foreach ($flux as $key => $val): ?>
		<li><a href="?flux=<?php echo $key; ?>"><?php echo $key; ?></a></li>
	<?php endforeach ?>

	</ul>

	<ul id='info'>
		<li><?php echo count($data); ?> articles disponibles pour les <?php echo (24*$jour); ?> dernières heures.</li>
	</ul>
</nav>

<?php foreach ($data as $val): ?>

	<article>
		<h1><?php echo $val["title"]; ?></h1>
		<h2><?php echo date('d/m/Y H:i', strtotime($val["pubDate"])).' - '; ?> <a href="<?php echo $val["link"]; ?>">Voir la source chez <?php echo $val['site']; ?></a></h2>
		<div><?php if(!empty($val['content'])) {echo $val['content'];}else{echo $val['description'];}?>
	</article>

<?php endforeach ?>

</body>
</html>