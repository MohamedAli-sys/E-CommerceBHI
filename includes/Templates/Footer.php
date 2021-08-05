
 <footer class="footer-distributed">

      <div class="footer-left">

        <h3>Sun<span>Moon</span></h3>

        <p class="footer-links">
          <a href="index.php">Home</a>
          ·
          <a href="showcat.php">Categories</a>
          .
          <a href="contact.php">Contact</a>
          ·
          <a href="about.php">About</a>
          ·
          <a href="faq.php">Faq</a>
          
          
        </p>

        <p class="footer-company-name">E-Commerce BHI47 &copy; 2015</p>

        <div class="footer-icons">

          <a href="#"><i class="fa fa-facebook"></i></a>
          <a href="#"><i class="fa fa-twitter"></i></a>
          <a href="#"><i class="fa fa-linkedin"></i></a>
          <a href="#"><i class="fa fa-github"></i></a>

        </div>

      </div>

      <div class="footer-right">

        <p>Contact Us :</p>

        <form action="contact.php" method="post">
          <input type="text" name="first_name" class="fname" placeholder="First Name" />
          <input type="text" name="last_name" class="lname" placeholder="Last Name" />
          <input type="text" name="email" class="email" placeholder="Valid Email" />
          <textarea name="message" class="message" placeholder="Message"></textarea>
          <button>Send</button>
        </form>
      </div>
    </footer>
  

		<script src="<?php echo $js; ?>jquery-1.12.1.min.js"></script>
		<script src="<?php echo $js; ?>jquery-ui.min.js"></script>
		<script src="<?php echo $js; ?>bootstrap.min.js"></script>
		<script src="<?php echo $js; ?>jquery.selectBoxIt.min.js"></script>
		<script src="<?php echo $js; ?>frontend.js"></script>
	</body>
</html>