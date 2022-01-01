-- REV 3
DELETE FROM `{tbl_prefix}config` WHERE name IN('keep_original');

INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES
	('keep_audio_tracks', '1'),
	('keep_subtitles', '1');

-- REV 4
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES
	('extract_subtitles', '1');

CREATE TABLE `{tbl_prefix}video_subtitle` (
	`videoid` bigint(20) NOT NULL,
	`number` varchar(2) NOT NULL,
	`title` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `{tbl_prefix}video_subtitle`
	ADD UNIQUE KEY `videoid` (`videoid`,`number`);

ALTER TABLE `{tbl_prefix}video_subtitle`
	ADD CONSTRAINT `{tbl_prefix}video_subtitle_ibfk_1` FOREIGN KEY (`videoid`) REFERENCES `{tbl_prefix}video` (`videoid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- REV 5
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES
	('extract_audio_tracks', '1');

CREATE TABLE `{tbl_prefix}video_audio_tracks` (
	`videoid` bigint(20) NOT NULL,
	`number` varchar(2) NOT NULL,
	`title` varchar(64) NOT NULL,
	`channels` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `{tbl_prefix}video_audio_tracks`
	ADD UNIQUE KEY `videoid` (`videoid`,`number`);

ALTER TABLE `{tbl_prefix}video_audio_tracks`
	ADD CONSTRAINT `{tbl_prefix}video_audio_tracks_ibfk_1` FOREIGN KEY (`videoid`) REFERENCES `{tbl_prefix}video` (`videoid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- REV 6
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES
	('player_subtitles', '1'),
	('subtitle_format', 'webvtt');

-- REV 7
DELETE FROM `{tbl_prefix}config` WHERE name = 'extract_audio_tracks';
DROP TABLE `{tbl_prefix}video_audio_tracks`;
