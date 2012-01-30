create table people(
	id	int(10) auto_increment not null,
	user_id varchar(25) not null,
	user_password	varchar(50) not null,
	full_name	varchar(20),
	disabled	char(1) not null,
	primary key(id)
);

insert into people(user_id,user_password,full_name,disabled) values('U1','1234','판매담당','N');