DROP TABLE IF EXISTS ahu_models;

-- DEFAULT UNHEX(REPLACE(UUID(), '-', ''))

CREATE TABLE ahu_models (
	id BINARY(16) PRIMARY KEY,  
    model	VARCHAR(512),
    maximum_air_volume	INT,
    base_height	INT,
    height_including_base	INT,
    width	INT,
    new_return_air_mixing_section	INT, -- 新回风混风段 
    primary_filter_section	INT,	-- 初效过滤段
    bag_medium_efficiency_filter_section	INT, -- 袋式中效过滤段
    primary_bag_medium_efficiency_filter_section	INT, -- 初+袋式中效过滤段 
    plate_medium_efficiency_filter_section	INT, -- 板式中效过滤段
    primary_plate_medium_efficiency_filter_section	INT, -- 初+板式中效过滤段
    box_medium_efficiency_filter_section	INT, -- 箱式中效过滤段 
    primary_box_medium_efficiency_filter_section	INT, -- 初+箱式中效过滤段
    cooling_coil_section	INT, -- 表冷段
    heating_section	INT, -- 加热段
    electric_heating_section	INT, -- 电加热段
    activated_carbon_filter_section	INT, --  活性炭过滤段
    heat_recycle_wheel_section	INT, --  热回收轮段(标准)（另需要配均流段）
    straight_plate_heat_pipe_section INT, --  直板热管段（标准段长）（另需要配均流段）
    humidification_section	INT,		 --  加湿段
     fan_section VARCHAR(512), 			--  风机段
    flow_equalization_section INT, 		-- 均流段
    empty_section	INT, -- 空段
    high_efficiency_filter_section	INT, -- 高效过滤段
    supply_air_section	INT, -- 送风段
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME
) ENGINE=InnoDB;

INSERT INTO ahu_models (
	id,
    model,
    maximum_air_volume,
    base_height,
    height_including_base,
    width,
    new_return_air_mixing_section, -- 新回风混风段 
    primary_filter_section,	-- 初效过滤段
    bag_medium_efficiency_filter_section, -- 袋式中效过滤段
    primary_bag_medium_efficiency_filter_section, -- 初+袋式中效过滤段 
    plate_medium_efficiency_filter_section, -- 板式中效过滤段
    primary_plate_medium_efficiency_filter_section, -- 初+板式中效过滤段
    box_medium_efficiency_filter_section, -- 箱式中效过滤段 
    primary_box_medium_efficiency_filter_section, -- 初+箱式中效过滤段
    cooling_coil_section, -- 表冷段
    heating_section, -- 加热段
    electric_heating_section, -- 电加热段
    activated_carbon_filter_section, -- 活性炭过滤段
    heat_recycle_wheel_section, -- 热回收轮段(标准)（另需要配均流段）
    straight_plate_heat_pipe_section, -- 直板热管段（标准段长）（另需要配均流段）
    humidification_section, -- 加湿段
    fan_section, -- 风机段
    flow_equalization_section, -- 均流段
    empty_section, -- 空段
    high_efficiency_filter_section, -- 高效过滤段
    supply_air_section -- 送风段
) VALUES 
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-0510F', '1564 ', '80 ', '655 ', '880 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1010F', '3127 ', '80 ', '960 ', '880 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1015F', '4553 ', '80 ', '960 ', '1155 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1020F', '6000 ', '80 ', '960 ', '1460 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1025F', '7680 ', '80 ', '960 ', '1790 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1030F', '10800 ', '80 ', '960 ', '2095 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1520F', '9011 ', '80 ', '1235 ', '1460 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1515F', '6831 ', '80 ', '1235 ', '1155 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1525F', '11521 ', '80 ', '1235 ', '1790 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1530F', '14031 ', '80 ', '1235 ', '2095 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1535F', '16747 ', '80 ', '1235 ', '2425 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1540F', '19257 ', '80 ', '1235 ', '2730 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-1545F', '21973 ', '80 ', '1235 ', '3060 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2010F', '6253 ', '80 ', '1540 ', '880 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2015F', '9106 ', '80 ', '1540 ', '1155 ', '480 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '480 ', '480 ', '360 ', '480 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2020F', '12015 ', '80 ', '1540 ', '1460 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2025F', '15361 ', '80 ', '1540 ', '1790 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2030F', '18708 ', '80 ', '1540 ', '2095 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2035F', '22330 ', '80 ', '1540 ', '2425 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2040F', '25676 ', '80 ', '1540 ', '2730 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2045F', '29297 ', '80 ', '1540 ', '3060 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2530F', '23385 ', '100 ', '1890 ', '2095 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2520F', '15361 ', '100 ', '1890 ', '1460 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2525F', '19200 ', '100 ', '1890 ', '1790 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2535F', '27911 ', '100 ', '1890 ', '2425 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2540F', '32095 ', '100 ', '1890 ', '2730 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-2550F', '45000 ', '100 ', '1890 ', '3365 ', '780 ', '180 ', '540 ', '540 ', '150 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3025F', '23385 ', '100 ', '2195 ', '1790 ', '600 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '540 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '600 ', '480 ', '360 ', '600 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3030F', '28474 ', '100 ', '2195 ', '2095 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3035F', '33494 ', '100 ', '2195 ', '2425 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3040F', '38514 ', '100 ', '2195 ', '2730 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3045F', '43927 ', '100 ', '2195 ', '3060 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3050F', '48966 ', '100 ', '2195 ', '3365 ', '780 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '780 ', '480 ', '360 ', '780 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3055F', '54374 ', '100 ', '2195 ', '3695 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3060F', '59391 ', '100 ', '2195 ', '4000 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3535F', '39000 ', '100 ', '2525 ', '2425 ', '900 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '900 ', '480 ', '360 ', '900 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3540F', '44927 ', '100 ', '2525 ', '2730 ', '900 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '900 ', '480 ', '360 ', '900 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3550F', '57085 ', '100 ', '2525 ', '3365 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3555F', '63464 ', '100 ', '2525 ', '3695 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-3560F', '69269 ', '100 ', '2525 ', '4000 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4035F', '44660 ', '120 ', '2850 ', '2425 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4040F', '51352 ', '120 ', '2850 ', '2730 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4045F', '58595 ', '120 ', '2850 ', '3060 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4050F', '60000 ', '120 ', '2850 ', '3365 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4055F', '72531 ', '120 ', '2850 ', '3695 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4060F', '77907 ', '120 ', '2850 ', '4000 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4550F', '73416 ', '120 ', '3180 ', '3365 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-4560F', '89119 ', '120 ', '3180 ', '4000 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-5050F', '81610 ', '120 ', '3485 ', '3365 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-5055F', '90662 ', '120 ', '3485 ', '3695 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 '),
	( UNHEX(REPLACE(UUID(), '-', '')), 'TFMC-5060F', '99030 ', '120 ', '3485 ', '4000 ', '1080 ', '180 ', '540 ', '540 ', '180 ', '180 ', '360 ', '360 ', '660 ', '300 ', '180 ', '480 ', '540 ', '540 ', '240 ', 'X', '1080 ', '480 ', '360 ', '1080 ');