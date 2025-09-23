CREATE TABLE IF NOT EXISTS dashboard_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    value_text VARCHAR(255) NOT NULL,
    trend_text VARCHAR(255) DEFAULT NULL,
    trend_type VARCHAR(32) DEFAULT 'neutral',
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_lessons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    level_label VARCHAR(100) NOT NULL,
    level_variant VARCHAR(32) DEFAULT 'neutral',
    skill VARCHAR(255) DEFAULT NULL,
    duration_text VARCHAR(100) DEFAULT NULL,
    duration_minutes INT UNSIGNED DEFAULT NULL,
    status_label VARCHAR(100) NOT NULL,
    status_variant VARCHAR(32) DEFAULT 'pending',
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS weekly_plan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    status_label VARCHAR(100) NOT NULL,
    status_variant VARCHAR(32) DEFAULT 'neutral',
    avatar_label VARCHAR(8) DEFAULT NULL,
    avatar_variant VARCHAR(32) DEFAULT 'neutral',
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type_variant VARCHAR(32) DEFAULT 'info',
    message VARCHAR(255) NOT NULL,
    time_label VARCHAR(100) DEFAULT NULL,
    time_iso VARCHAR(32) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS monthly_progress (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL,
    progress_percent TINYINT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS teacher_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    value_text VARCHAR(255) NOT NULL,
    trend_text VARCHAR(255) DEFAULT NULL,
    trend_type VARCHAR(32) DEFAULT 'neutral',
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS scheduled_content (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type_label VARCHAR(100) NOT NULL,
    type_variant VARCHAR(32) DEFAULT 'neutral',
    scheduled_at VARCHAR(100) DEFAULT NULL,
    scheduled_at_datetime DATETIME DEFAULT NULL,
    status_label VARCHAR(100) NOT NULL,
    status_variant VARCHAR(32) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admin_metrics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    value_text VARCHAR(255) NOT NULL,
    trend_text VARCHAR(255) DEFAULT NULL,
    trend_type VARCHAR(32) DEFAULT 'neutral',
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS team_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    avatar_label VARCHAR(8) DEFAULT NULL,
    avatar_variant VARCHAR(32) DEFAULT 'neutral',
    role_label VARCHAR(100) NOT NULL,
    role_variant VARCHAR(32) DEFAULT 'neutral',
    status_label VARCHAR(100) NOT NULL,
    status_variant VARCHAR(32) DEFAULT 'pending',
    permissions_summary VARCHAR(255) DEFAULT NULL,
    actions VARCHAR(255) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS role_definitions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    badge_variant VARCHAR(32) DEFAULT 'neutral',
    badge_label VARCHAR(100) DEFAULT NULL,
    feature_list TEXT DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permission_groups (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    sort_order INT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permission_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_id INT UNSIGNED NOT NULL,
    label VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    sort_order INT UNSIGNED DEFAULT 0,
    CONSTRAINT fk_permission_items_group FOREIGN KEY (group_id)
      REFERENCES permission_groups(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
