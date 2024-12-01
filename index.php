<!DOCTYPE html>
<html>
<head>
    <title>Login Delegation Application</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <?php require_once('menu.php'); ?>
  <h1>Welcome to the Login Delegation App</h1>
    
	<div class="wide">
		<?php echo messages_to_show(); ?>

		<p>Delegarea autentificării, cunoscută și sub denumirea de „conectare prin proxy” sau „conectare unică”, este o metodă de a permite unui utilizator să se autentifice cu un serviciu și apoi să folosească acea autentificare pentru a accesa alte servicii fără a fi necesar să-și furnizeze din nou acreditările.</p>
		
		<p>Ideea de bază este că un utilizator se conectează la un serviciu central, care apoi autentifică utilizatorul și emite un token care poate fi folosit pentru a accesa alte servicii. Acest token, adesea denumit „token purtător”, este apoi trimis împreună cu solicitările ulterioare către alte servicii pentru a dovedi identitatea utilizatorului.</p>
		
		<p>Când un utilizator dorește să acceseze un serviciu, este redirecționat către un serviciu central de autentificare, unde își furnizează acreditările. Odată autentificat, serviciul central generează un token care reprezintă sesiunea autentificată a utilizatorului și acest token este transmis serviciului pe care utilizatorul dorește să îl acceseze. Serviciul poate folosi apoi acest token pentru a verifica identitatea utilizatorului fără a solicita utilizatorului să furnizeze din nou acreditările.</p>
		
		<p>Această abordare permite utilizatorilor să aibă un singur set de acreditări pentru mai multe servicii, îmbunătățindu-și experiența și reducând riscul parolelor uitate sau atacurilor de phishing.</p>
	</div>
</body>
</html>
