<?php
/**
 * Profile page
 *
 * If logged in, this page allows user to edit own information.
 * If not logged in, or different user from current profile,
 * this page displays public information for a particular user.
 *
 * @author Hannah Turner
 * @since 0.0.6
 *
 * @todo Validate input fields
 */

Global $the_title;
$the_title='Profile';
include_once ('header.php');

$u_firstname=$_POST['u_first'];
$u_first=get_user($u_firstname);
$u_lastname=$_POST['u_last'];
$u_last=get_user($u_lastname)?>

<div id="primary" class="content-area container">
      <div id="content" class="site-content col-lg-12 col-md-12" role="main">
        <div class="row">
          <article class="page type-page status-draft hentry col-lg-12 col-md-12 col-sm-12">
            <header class="entry-header">
              <h1 class="entry-title"><?php echo $the_title; ?></h1>
            </header><!-- .entry-header -->

            <div class="entry-content">

<section>
  <h1>My Profile</h1>
    <?php echo '$u_first . $u_last'; ?>
  <h2>My Contact Info</h2>
        <h3>Email:</h3>
</section>


<FORM METHOD="LINK" action="editprofile.php">
<p>
  <input type="submit"  value="Edit Profile" name="edit profile" />
</p>
</FORM>

 </div><!-- .entry-content -->
          </article>
        </div><!-- .row -->
      </div><!-- #content -->
    </div><!-- #primary -->

<?php include_once('footer.php'); ?>
