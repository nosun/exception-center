-- Apply database/schema.sql first, then these processing tables.
CREATE TABLE exception_groups (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, fingerprint CHAR(64) NOT NULL,
  project_code VARCHAR(64) NOT NULL, repository_code VARCHAR(128) NOT NULL, instance_code VARCHAR(64) NOT NULL,
  environment VARCHAR(32) NOT NULL, category VARCHAR(32) NOT NULL, exception_type VARCHAR(160) NOT NULL,
  severity VARCHAR(8) NOT NULL, first_seen_at DATETIME(6) NOT NULL, last_seen_at DATETIME(6) NOT NULL,
  occurrence_count BIGINT UNSIGNED NOT NULL DEFAULT 0, sample_event_id CHAR(36) NOT NULL, status VARCHAR(32) NOT NULL DEFAULT 'new',
  assigned_agent VARCHAR(128) NULL, assigned_at DATETIME(6) NULL, lease_until DATETIME(6) NULL,
  resolved_at DATETIME(6) NULL, resolution_code VARCHAR(64) NULL, resolution_summary VARCHAR(2000) NULL,
  created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) NOT NULL,
  UNIQUE KEY exception_groups_active (fingerprint,instance_code,environment,status), INDEX exception_groups_status (status,last_seen_at)
);
CREATE TABLE exception_dispatches (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, group_id BIGINT UNSIGNED NOT NULL, dispatch_id CHAR(36) NOT NULL UNIQUE,
  agent_code VARCHAR(128) NOT NULL, project_code VARCHAR(64) NOT NULL, repository_code VARCHAR(128) NOT NULL,
  workspace VARCHAR(512) NULL, prompt_payload_json JSON NOT NULL, status VARCHAR(32) NOT NULL DEFAULT 'pending', attempts INT UNSIGNED NOT NULL DEFAULT 0,
  leased_at DATETIME(6) NULL, lease_until DATETIME(6) NULL, started_at DATETIME(6) NULL, finished_at DATETIME(6) NULL,
  result_code VARCHAR(64) NULL, result_summary VARCHAR(2000) NULL, result_payload_json JSON NULL, last_error VARCHAR(2000) NULL,
  created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) NOT NULL, INDEX exception_dispatches_claim (status,lease_until)
);
CREATE TABLE exception_notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, group_id BIGINT UNSIGNED NOT NULL, channel_code VARCHAR(64) NOT NULL,
  notification_type VARCHAR(64) NOT NULL, dedupe_key VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL DEFAULT 'pending',
  attempts INT UNSIGNED NOT NULL DEFAULT 0, sent_at DATETIME(6) NULL, last_error VARCHAR(2000) NULL, payload_summary_json JSON NULL,
  created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) NOT NULL, UNIQUE KEY exception_notifications_dedupe (dedupe_key)
);
