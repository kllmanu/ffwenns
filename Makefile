export PATH := bin:$(PATH)

TAR_NAME := ffwenns-$(shell date +%s).tar.gz

serve:
	symfony server:start --allow-http

copy:
	# rclone copy --progress contao ffwenns:public_html/contao
	# rclone copy --progress files ffwenns:public_html/files
	# rclone copy --progress templates ffwenns:public_html/templates

backup:
	console contao:backup:create
	tar -czvf $(TAR_NAME) contao/ files/ templates/ var/backups/

.PHONY: serve copy backup
