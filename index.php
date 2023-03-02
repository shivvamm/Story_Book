<!DOCTYPE html>
<html>
<head>
	<title>PHP Single Page Website</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>


<nav class="navbar sticky-top navbar-light bg-light border-black">
  <a class="navbar-brand font-sans text-4xl not-italic font-semibold tracking-wide pl-5" href="#">STORY</a>
  <ul class="nav justify-content-end">
  <li class="nav-item">
    <a class="nav-link  text-blue hover:text-red" href="#post">Posts</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-blue hover:text-red" href="#create">Create Post</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-blue hover:text-red" href="https://www.linkedin.com/in/shivampandey27">Contact Us</a>
  </li>
</ul>
</nav>



	<div class="container">
    <div class="max-h-full max-w-full" id="create">
		<form method="post"  class="mt-20" enctype="multipart/form-data">
			<div class="form-group">
				<label for="content" class="form-label">Enter your content:</label>
				<textarea id="content" class="form-control border-black" name="content"></textarea>
			</div>
			<div class="form-group">
				<label for="image" class="form-label">Upload an image:</label>
				<input type="file" class="form-control border-black" id="image" name="image">
			</div>
			<div class="form-group">
				<label for="video" class="form-label">Upload a video:</label>
				<input type="file" class="form-control border-black" id="video" name="video">
			</div>
			<div class="submission">
				<button type="submit" class="btn btn-secondary btn-lg btn-block">Submit</button>
			</div>
		</form>
        <div>
        <div class="container mt-40" id="post">
		<h2>Recent Posts</h2>
		<div class="posts">
			<?php
			// Connect to the database
			$db = new PDO("mysql:host=host;dbname=DB", "username", "password");
            if (!$db) {
    die('Database connection failed: ' . $db->errorInfo()[2]);
}

			// Retrieve all posts
// Retrieve all posts from the database
			$query = $db->query("SELECT * FROM posts ORDER BY created_at DESC");
			$posts = $query->fetchAll(PDO::FETCH_ASSOC);

			// Loop through each post and display it
			foreach ($posts as $post) {
				echo "<div class='post card'>";
				if (!empty($post['image'])) {
					echo "<img src='{$post['image']}' alt='Post image' style='padding-bottom:5px'>";
				}
				if (!empty($post['video'])) {
					echo "<video src='{$post['video']}' controls></video>";
				}
				echo "<p>{$post['content']}</p>";
				echo "</div>";
			}

			// Handle form submission
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Retrieve user input
				$content = $_POST['content'];

				// Check if an image was uploaded
				if (!empty($_FILES['image']['name'])) {
					$image_path = 'uploads/' . $_FILES['image']['name'];
					move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
				} else {
					$image_path = '';
				}

				// Check if a video was uploaded
				if (!empty($_FILES['video']['name'])) {
					$video_path = 'uploads/' . $_FILES['video']['name'];
					move_uploaded_file($_FILES['video']['tmp_name'], $video_path);
				} else {
					$video_path = '';
				}



				// Insert post into the database
				$query = $db->prepare("INSERT INTO posts (content, image, video) VALUES (:content, :image, :video)");
				$query->bindParam(':content', $content);
				$query->bindParam(':image', $image_path);
				$query->bindParam(':video', $video_path);
				$query->execute();

                

				// Redirect to the current page to refresh the posts
				header('Location: ' . $_SERVER['PHP_SELF']);
				exit;
			}
			?>
		</div>
        </div>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>