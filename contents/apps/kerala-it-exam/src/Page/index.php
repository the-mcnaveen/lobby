<?php
$this->addScript("register.js");
?>
<div class="contents">
  <h1>IT Exam</h1>
  <p>This App is an exact copy of the Computer Examination Software used at all Government schools in Kerala.</p>
  <p>You can use this app to practice the examination.</p>
  <p>Fill up the required information and start the exam.</p>
  <form id="student_register" method="POST" action="<?php echo \Lobby::u();?>">
    <label>
      <p>Select Class</p>
      <select name="class">
        <option name="eight">8</option>
        <option name="nine">9</option>
        <option name="ten">10</option>
      </select>
    </label>
    <label>
      <p>Select Division</p>
      <select name="div">
        <option name="a">A</option>
        <option name="b">B</option>
        <option name="c">C</option>
        <option name="d">D</option>
      </select>
    </label>
    <label>
      <p>Roll Number</p>
      <input type="number" name="roll" value="1" />
    </label>
    <p>
      <p>Your Register Number is :</p>
      <blockquote id="register_number">8A1</blockquote>
    </p>
    <button clear="" name="submit" class="button red">Start Exam</button>
  </form>
  <?php
  if(isset($_SESSION['kerala-it-exam-rid'])){
    \Lobby::redirect("/app/kerala-it-exam/exam");
  }
  if(isset($_POST['submit'])){
    $class = $_POST['class'];
    $div = $_POST['div'];
    $roll = $_POST['roll'];
    
    $register_no = $class . $div . $roll; // Register Number
    $_SESSION['kerala-it-exam-rid'] = $register_no;
    $_SESSION['kerala-it-exam-class'] = $class;
  ?>
    <h1>Please wait...</h1>
    <script>
    localStorage["end_time"] = "invalid";
    delete localStorage["end_time"];
    setTimeout(function(){
      window.location = "<?php echo \Lobby::u("/app/kerala-it-exam");?>";
    }, 50);
    </script>
  <?php
  }
  ?>
</div>
