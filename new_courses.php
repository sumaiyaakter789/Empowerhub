<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <style>
       body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('b6.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: white;
}

.glass {
    background-color: rgba(50, 50, 54, 0.5); /* Semi-transparent color */
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3); /* Adds a subtle border */
    padding: 20px;
    border-radius: 10px; /* Smooth corners */
    backdrop-filter: blur(2px); /* Creates the blur effect */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); 
    width:65%;
    margin-left: 220px/* Adds a slight shadow for depth */
}

/* Input fields, select dropdowns, and textareas with glassmorphism style */
/* Input fields, select dropdowns, and textareas with glassmorphism style */
input[type="text"],
input[type="number"],
input[type="file"],
select,
input[type="datetime-local"] {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* White text */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Inner spacing */
    font-size: 16px; /* Font size for input fields */
    width: 100%; /* Ensure full width */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus,
input[type="datetime-local"]:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* File input field style */
input[type="file"] {
    padding: 10px; /* Padding for consistency */
}

input[type="file"]:focus {
    background-color: rgba(255, 255, 255, 0.2); /* Darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Remove custom background for option elements */
select option {
    background-color: #545c56; /* Keep the option background as you want */
    color: white; /* Text color for the options */
}


/* Buttons styling */
.custom-button {
    background-color: rgba(50, 50, 54, 0.8);
    color: white;
    padding: 10px 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-button:hover {
    background-color: rgba(80, 80, 90, 0.9);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.half-width {
    width: 48%;
    display: inline-block;
    margin-right: 2%;
}

.full-width {
    width: 100%;
    display: inline-block;
}

select {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded edges */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
}

select:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Remove custom background for option elements */
select option {
    background-color: #545c56; /* Keep the option background as you want */
    color: white; /* Text color for the options */
}

/* Remove the white background for the description editor */
#description_editor {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

/* Apply the same glassmorphism effect to the content textarea */
textarea#content {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
    width: 100%; /* Ensure full width */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

textarea#content:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Course type select dropdown with glassmorphism style */
#courseType {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded edges */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
    width: 100%; /* Ensure full width */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

#courseType:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Remove custom background for option elements */
#courseType option {
    background-color: #545c56; /* Option background */
    color: white; /* Option text color */
}



    </style>
</head>
<body>

<div class="container">
    <div class="glass">
        <h1 class="text-center text-white mb-4">Add New Course</h1>
        <form method="POST" action="save_course.php" enctype="multipart/form-data">
            <div class="mb-3 half-width">
                <label for="courseType" class="form-label">Course Type</label>
                <select name="course_type" id="courseType" class="form-select" onchange="showCourseFields()">
                    <option value="live">Live Course</option>
                    <option value="video">Video Course</option>
                    <option value="text">Text Course</option>
                </select>
            </div>
            <div class="mb-3 half-width">
                <label for="title" class="form-label">Course Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3 half-width">
                <label for="thumbnail" class="form-label">Thumbnail</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" required>
            </div>
            <div class="mb-3 full-width">
                <label for="description" class="form-label">Description</label>
                <div id="description_editor" class="text-editor" contenteditable="true"></div>
                <input type="hidden" name="description" id="description">
            </div>
            <div class="mb-3 half-width">
                <label for="price" class="form-label">Course Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>

            <!-- Live Course Fields -->
            <div id="liveCourseFields" class="optional-fields">
                <div class="mb-3 half-width">
                    <label for="classTime" class="form-label">Class Time</label>
                    <input type="datetime-local" class="form-control" id="classTime" name="class_time">
                </div>
                <div class="mb-3 half-width">
                    <label for="classPlatform" class="form-label">Class Platform</label>
                    <input type="text" class="form-control" id="classPlatform" name="class_platform">
                </div>
            </div>

            <!-- Video Course Fields -->
            <div id="videoCourseFields" class="optional-fields">
                <div class="mb-3 half-width">
                    <label for="videoFile" class="form-label">Upload Video</label>
                    <input type="file" class="form-control" id="videoFile" name="video_file" accept="video/*">
                </div>
            </div>

            <!-- Text Course Fields (for content editing) -->
            <div id="textCourseFields" class="optional-fields">
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Course Content</label>
                    <div class="mb-2 flex items-center space-x-2 text-editor-toolbar">
                        
                    </div>
                    <div id="content" contenteditable="true" class="text-editor"></div>
                </div>
            </div>

            <button type="submit" class="btn  custom-button">Create Course</button>
        </form>
    </div>
</div>

<script>
    function showCourseFields() {
        var courseType = document.getElementById('courseType').value;

        document.getElementById('liveCourseFields').style.display = 'none';
        document.getElementById('videoCourseFields').style.display = 'none';
        document.getElementById('textCourseFields').style.display = 'none';

        if (courseType === 'live') {
            document.getElementById('liveCourseFields').style.display = 'block';
        } else if (courseType === 'video') {
            document.getElementById('videoCourseFields').style.display = 'block';
        } else if (courseType === 'text') {
            document.getElementById('textCourseFields').style.display = 'block';
        }
    }

    showCourseFields();

    var quill = new Quill('#content', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
            ]
        }
    });

    document.querySelector('form').onsubmit = function() {
        document.querySelector('#description').value = quill.root.innerHTML;
    };
</script>

</body>
</html>
