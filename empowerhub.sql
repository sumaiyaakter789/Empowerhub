CREATE TABLE admin (
  admin_id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE signup (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('student', 'instructor', 'organization') NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT UNSIGNED,
    course_type ENUM('live', 'video', 'text') NOT NULL,
    title VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    class_time DATETIME DEFAULT NULL,
    class_platform VARCHAR(255) DEFAULT NULL,
    video_file VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES signup(id)
);

CREATE TABLE articles (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT(11) UNSIGNED,
    admin_id INT(11),
    counselor_id INT(11),
    title VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    description TEXT,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES signup(id),
    FOREIGN KEY (admin_id) REFERENCES admin(admin_id),
    FOREIGN KEY (counselor_id) REFERENCES counselors(counselor_id)
);

CREATE TABLE products (
    product_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    product_type ENUM('software', 'device', 'stationary') NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    total DECIMAL(10, 2) NOT NULL,
    delivery_charge DECIMAL(10, 2) NOT NULL,
    voucher_discount DECIMAL(10, 2) DEFAULT 0,
    final_total DECIMAL(10, 2) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES signup(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT UNSIGNED,
    course_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('not completed', 'completed') DEFAULT 'not completed',
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percentage DECIMAL(5, 2) NOT NULL,
    expiry_date DATE NOT NULL
);

CREATE TABLE subscriptions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    rating INT(1) CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    status ENUM('approved', 'pending', 'declined') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES signup(id) ON DELETE CASCADE
);

CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bname VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL
);

CREATE TABLE skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    skill_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES signup(id)
);

CREATE TABLE opportunities (
    opportunity_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    opportunity_name VARCHAR(255) NOT NULL,
    description TEXT,
    required_skill VARCHAR(255) NOT NULL,
    requirements TEXT,
    location VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES signup(id)
);

CREATE TABLE counselors (
    counselor_id INT AUTO_INCREMENT PRIMARY KEY,
    counselor_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED,
    student_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    appointment_date DATE NOT NULL,
    special_note TEXT,
    status ENUM('pending', 'received') DEFAULT 'pending',
    counselor_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    meeting_link VARCHAR(500) DEFAULT NULL;
    FOREIGN KEY (student_id) REFERENCES signup(id),
    FOREIGN KEY (counselor_id) REFERENCES counselors(counselor_id) ON DELETE SET NULL
);

CREATE TABLE notices (
  notice_id INT(11) AUTO_INCREMENT PRIMARY KEY,
  admin_id INT(11) NOT NULL,
  notice_date DATE NOT NULL,
  heading VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  event_type ENUM('online', 'offline') NOT NULL,
  event_detail VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES admin(admin_id) ON DELETE CASCADE
);

CREATE TABLE posts (
    post_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    posted_by INT(11) UNSIGNED NOT NULL,
    post_content TEXT NOT NULL,
    reaction_count INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES signup(id) ON DELETE CASCADE
);

CREATE TABLE comments (
    comment_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT(11) UNSIGNED NOT NULL,
    commented_by INT(11) UNSIGNED NOT NULL,
    comment_content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (commented_by) REFERENCES signup(id) ON DELETE CASCADE
);

CREATE TABLE reactions (
    reaction_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT(11) UNSIGNED NOT NULL,
    reacted_by INT(11) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (reacted_by) REFERENCES signup(id) ON DELETE CASCADE
);

