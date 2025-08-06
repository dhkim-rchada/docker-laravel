-- MySQL root 계정으로 접속하여 실행됩니다.

-- rchada 사용자에게 모든 데이터베이스에 대한 모든 권한 부여
-- 'Dkfckek1234!@#$'는 docker-compose.yml에 설정된 비밀번호와 일치해야 합니다.
-- `%`는 모든 외부 IP 주소로부터의 접속을 허용한다는 의미입니다.
GRANT ALL PRIVILEGES ON *.* TO 'rchada'@'%' IDENTIFIED BY 'Dkfckek1234!@#$';

-- 변경 사항 적용
FLUSH PRIVILEGES;