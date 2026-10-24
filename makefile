.PHONY: build bash start

start: build
	$(MAKE) bash

build:
	docker build -t imdhemy/appstore-iap .

bash:
	docker run --rm -it --name appstore-iap-container -v "$(CURDIR):/var/www" imdhemy/appstore-iap bash

%:
	@:
