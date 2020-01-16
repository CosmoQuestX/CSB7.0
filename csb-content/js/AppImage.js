function AppImage(data, applicationName, csbApp) {
    this.csbApp = csbApp;
    this.fileLocation = data.image.file_location;
    this.id = data.image.id;
    this.sunAngle = data.image.sun_angle;
    this.image = new Image();
    this.visibleImage = new Image();
    this.image.src = this.fileLocation;
    this.visibleImage.src = this.fileLocation;
    this.marks = [];
    this.startTime = 0;
    this.endTime = 0;
    this.userData = data.user_data;
    this.requiredNumberOfTutorialCraters = 0;


    if (this.csbApp.applicationName == "simply_craters_verification") {
        var newCrater = new Mark("crater", data['crater'].x, data['crater'].y, data['crater'].diameter);
        newCrater.id = data['crater'].id;
        newCrater.alpha = .3;
        this.marks.push(newCrater);
        this.csbApp.appInterface.setCamera(newCrater.x, newCrater.y, 450 / newCrater.diameter / 4);
    } else {
        if (isSet(data['machine_name']))
            this.machineName = data['machine_name'];

        //this.csbApp.appInterface.displayBadges(this.userData['earned_badges']);

        if (isSet(data.marks)) {
            data.marks = JSON.parse(data.marks);
            for (var i = 0; i < data.marks.length; i++) {
                var markData = data.marks[i];
                var newMark = Mark.createMark(markData['type'], markData.x, markData.y, markData.diameter, csbApp.appInterface);
                if (isSet(markData['id']))
                    newMark.id = markData.id;
                if (markData['is_shared'] == true)
                    newMark.isShared = true;
                if (isSet(markData['sub_type']))
                    newMark.subType = markData['sub_type'];
                if (isSet(markData['owner']))
                    newMark.owner = markData['owner'];
                this.marks.push(newMark);
            }
        }
    }

    this.adjustContrast = function (contrast) {
        if (contrast > 255)
            contrast = 255;

        var factor = (256 * (contrast + 255)) / (255 * (256 - contrast));

        for (var i = 0; i < data.length; i += 4) {
            this.visibleImage.data[i] = factor * (this.image.data[i] - 128) + 128;
            this.visibleImage.data[i + 1] = factor * (this.image.data[i + 1] - 128) + 128;
            this.visibleImage.data[i + 2] = factor * (this.image.data[i + 2] - 128) + 128;
        }
    };

    this.adjustColor = function (color, amount) {
        for (var i = 0; i < data.length; i += 4) {
            var r = this.image.data[i];
            var g = this.image.data[i + 1];
            var b = this.image.data[i + 2];
            var naturalPercent = (Math.sin(r / 256.0 * Math.PI - Math.PI / 4) + 1) / 2;
            var totalPercent = amount * naturalPercent;
            this.visibleImage.data[i] = 0;
            this.visibleImage.data[i + 1] = g * (1 - totalPercent) + color.g * totalPercent;
            this.visibleImage.data[i + 2] = b * (1 - totalPercent) + color.b * totalPercent;
        }
    };

    this.getClosestCraterTo = function (mousePosition) {
        return this.getClosestMarkTo(mousePosition, "crater", true);
    };

    this.getClosestFeatureTo = function (mousePosition) {
        return this.getClosestMarkTo(mousePosition, "crater", false);
    };

    this.getClosestMarkTo = function (mousePosition, type, wantType) {
        var closestMark = null;
        var closestMarkDistance = 9999999;
        var distance, i, j;
        for (i = 0; i < this.marks.length; i++) {
            var mark = this.marks[i];
            if (mark.isStatic == false) {
                if (mark.type == "paint_mark") {
                    // Do nothing if mark is a paint mark
                } else if (mark.type == "linear_feature" || mark.type == "blanket") {
                    for (j = 0; j < mark.points.length; j++) {
                        var subMark = mark.points[j];
                        distance = distanceBetween(mousePosition, subMark);
                        if (distance < closestMarkDistance && mark.owner == "user") {
                            if (isSet(type) && isSet(wantType)) {
                                if (wantType && mark.type == type || !wantType && mark.type != type) {
                                    closestMarkDistance = distance;
                                    closestMark = mark;
                                }
                            } else {
                                closestMarkDistance = distance;
                                closestMark = mark;
                            }
                        }
                    }
                } else {
                    distance = distanceBetween(mousePosition, mark);
                    if (distance < closestMarkDistance && mark.owner == "user") {
                        if (isSet(type) && isSet(wantType)) {
                            if (wantType && mark.type == type || !wantType && mark.type != type) {
                                closestMarkDistance = distance;
                                closestMark = mark;
                            }
                        } else {
                            closestMarkDistance = distance;
                            closestMark = mark;
                        }
                    }
                }
            }
        }
        return closestMark;
    };

    this.deleteMark = function (mark) {
        this.marks.splice(this.marks.indexOf(mark), 1);
    };

    this.drawSun = function (canvas, context) {
        this.sunAngle = 0;
        if (!isSet(canvas) || !isSet(context) || !isSet(this.sunAngle))
            return;

        var angle = this.sunAngle;
        while (angle < 0) angle += Math.PI * 2;
        while (angle >= Math.PI * 2) angle -= Math.PI * 2;

        var sunPosition = {x: 0, y: 0};

        if (angle <= Math.PI / 4 || angle >= 7 * Math.PI / 4) {
            // right side
            sunPosition.x = canvas.width;
            sunPosition.y = -(canvas.height / 2) * Math.tan(angle) + (canvas.height / 2);
        } else if (angle <= 5 * Math.PI / 4 && angle >= 3 * Math.PI / 4) {
            // left side
            sunPosition.x = 0;
            sunPosition.y = (canvas.height / 2) * Math.tan(angle) + (canvas.height / 2);
        } else if (angle <= 3 * Math.PI / 4 && angle >= Math.PI / 4) {
            // top side
            sunPosition.x = (canvas.width / 2) / Math.tan(angle) + (canvas.width / 2);
            sunPosition.y = 0;
        } else {
            sunPosition.x = -(canvas.width / 2) / Math.tan(angle) + (canvas.width / 2);
            sunPosition.y = canvas.height;
        }

        var sunImage = this.csbApp.appInterface.icons.sunImage;
        context.globalAlpha = 1;
        context.translate(sunPosition.x, sunPosition.y);
        context.drawImage(sunImage, -sunImage.width / 2, -sunImage.height / 2, sunImage.width, sunImage.height);
        context.translate(-sunPosition.x, -sunPosition.y);
    };
}