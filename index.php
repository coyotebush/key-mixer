<!DOCTYPE html>
<html>
<head>
  <title>Key Mixer</title>
</head>
<body>
  <h1>Key Mixer</h1>
<?php
$Keyring = dirname(__FILE__) . '/data/keyring.gpg';
$Homedir = dirname(__FILE__) . '/data/gnupg';
$Gpg = '/usr/bin/gpg2';

if (isset($_POST['key'])) {
  echo '<section>';
  $command = "$Gpg --homedir $Homedir --no-default-keyring --keyring $Keyring --import";
  $fds = array(
    0 => array('pipe', 'r'),
    1 => array('pipe', 'w'),
    2 => array('pipe', 'w')
  );
  $process = proc_open($command, $fds, $pipes);
  if (is_resource($process)) {
    fwrite($pipes[0], $_POST['key']);
    fclose($pipes[0]);
    echo '<pre>';
    echo htmlspecialchars(stream_get_contents($pipes[1]));
    echo htmlspecialchars(stream_get_contents($pipes[2]));
    echo '</pre>';
    fclose($pipes[1]);
    fclose($pipes[2]);
    $ret = proc_close($process);
    if ($ret != 0) {
      echo "<p>gpg returned $ret :(</p>";
    }
  }
  else {
    echo '<p>couldn\'t run gpg :(</p>';
  }
  echo '</section>';
}
?>
  <section>
    <h2>Submit a key</h2>
    <form action="" method="POST">
      <textarea name="key" placeholder="ASCII-armored GPG data" rows="10" cols="66"></textarea>
      <p>
        <input type="submit" value="Submit">
      </p>
    </form>
  </section>
  <section>
    <h2>Download the keyring</h2>
    <a href="data/keyring.gpg">keyring.gpg</a>
  </section>
</body>
</html>
<!-- vim: set ts=2 sts=2 sw=2 et: -->
