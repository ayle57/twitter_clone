diagram:
	dot -Tpng docs/classDiagram.dot -o docs/classDiagram.png
scss:
	php bin/console sass:build --watch

dev:
	symfony serve -d
	bun dev
