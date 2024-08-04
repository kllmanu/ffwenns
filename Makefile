export PATH := bin:$(PATH)

TAR_NAME := ffwenns-$(shell date +%s).tar.gz

serve:
	symfony server:start --allow-http

auth:
    @echo "Enter your short-lived user access token from: https://developers.facebook.com/tools/explorer"
    @read -p "Facebook Exchange Token: " input
    FACEBOOK_USER_ACCESS_TOKEN=$$(https graph.facebook.com/oauth/access_token grant_type==fb_exchange_token \
        client_id==$(FACEBOOK_CLIENT_ID) \
        client_secret==$(FACEBOOK_CLIENT_SECRET) \
        fb_exchange_token==$$input | jq -r '.access_token')
    FACEBOOK_PAGE_ACCESS_TOKEN=$$(https graph.facebook.com/ffwenns \
        fields==access_token access_token==$$FACEBOOK_USER_ACCESS_TOKEN | jq -r '.access_token')
    @echo
    @echo "Your page access token is: $$FACEBOOK_PAGE_ACCESS_TOKEN"

copy:
	# rclone copy --progress contao ffwenns:public_html/contao
	# rclone copy --progress files ffwenns:public_html/files
	# rclone copy --progress templates ffwenns:public_html/templates

backup:
	console contao:backup:create
	tar -czvf $(TAR_NAME) contao/ files/ templates/ var/backups/

.PHONY: serve auth copy backup
