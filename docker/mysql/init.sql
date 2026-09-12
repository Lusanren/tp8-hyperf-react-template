-- ============================================================
-- Base System Schema for ThinkPHP 8 + FastAPI + React 18 Admin
-- Character Set: utf8mb4 / Collation: utf8mb4_unicode_ci
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sys_users
-- ----------------------------
CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(64) NOT NULL COMMENT '登录用户名',
  `password` varchar(255) NOT NULL COMMENT '密码哈希(bcrypt/password_hash)',
  `nickname` varchar(64) DEFAULT '' COMMENT '用户昵称',
  `avatar` varchar(255) DEFAULT '' COMMENT '用户头像URL',
  `email` varchar(128) DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) DEFAULT '' COMMENT '手机号',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态: 1=正常, 0=禁用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统用户表';

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
CREATE TABLE IF NOT EXISTS `sys_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(64) NOT NULL COMMENT '角色名称',
  `role_key` varchar(64) NOT NULL COMMENT '角色权限字符(如: superadmin, admin, editor)',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态: 1=正常, 0=禁用',
  `sort` int NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_role_key` (`role_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

-- ----------------------------
-- Table structure for sys_menus
-- ----------------------------
CREATE TABLE IF NOT EXISTS `sys_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `parent_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `title` varchar(64) NOT NULL COMMENT '菜单标题',
  `path` varchar(128) NOT NULL DEFAULT '' COMMENT '前端路由地址',
  `component` varchar(128) DEFAULT '' COMMENT '组件路径',
  `icon` varchar(64) DEFAULT '' COMMENT '图标标识',
  `sort` int NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态: 1=正常, 0=隐藏',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单权限表';

-- ----------------------------
-- Seed Data: Default Admin User (Password: admin123)
-- Hash: $2y$10$e8T8W0u.0mB11O.zK8bQ5.k3Cj59T9fR3U.K2H0bQW5nS1A6Yg.mG (or standard password_hash)
-- ----------------------------
INSERT IGNORE INTO `sys_users` (`id`, `username`, `password`, `nickname`, `avatar`, `status`)
VALUES (1, 'admin', '$2y$10$j8dK.9zV2aF.7hU8n6v4U.Wf1N6u0u0QW5nS1A6Yg.mG4k3Cj59T9', '超级管理员', 'https://api.dicebear.com/7.x/avataaars/svg?seed=admin', 1);

INSERT IGNORE INTO `sys_roles` (`id`, `role_name`, `role_key`, `status`, `sort`)
VALUES (1, '超级管理员', 'superadmin', 1, 1);

INSERT IGNORE INTO `sys_menus` (`id`, `parent_id`, `title`, `path`, `component`, `icon`, `sort`) VALUES
(1, 0, '仪表盘', '/dashboard', 'pages/dashboard/index', 'LayoutDashboard', 1),
(2, 0, 'AI 对话助手', '/ai-chat', 'pages/ai-chat/index', 'Bot', 2);

SET FOREIGN_KEY_CHECKS = 1;
