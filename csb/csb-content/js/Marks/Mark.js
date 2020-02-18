function Mark(type, x, y, appInterface) {
    this.appInterface = appInterface;
    this.type = type;
    this.subType = null;
    this.x = x;
    this.y = y;
    this.diameter = 10;
    this.id = null;
    this.machineId = null;
    this.lastChangeTime = 0;
    this.owner = "user";
    this.isShared = false;
    this.isBeingResized = false;
    this.isBeingMoved = false;
    this.isBeingMade = false;
    this.isSelected = false;
    this.creationStartPosition = null;
    this.relativeMousePosition = {x: 0, y: 0};
    this.relativeMouseDistance = 0;
    this.alpha = 1;
    this.isStatic = false; // Used for immobile craters like tutorial craters
    this.status = "normal"; // Options: normal, correct, too_far, too_small, too_large, way_too_far, premarked, machine

    this.draw = function (context) {
        console.log("Mark type not specified.");
    };

    this.displayMarkType = function (globalMousePosition) {
        if (globalMousePosition.y < 0)
            $("#marker-type").css('top', globalMousePosition.y + 125 + 'px');
        else
            $("#marker-type").css('top', globalMousePosition.y - 100 + 'px');
        $("#marker-type").css('left', globalMousePosition.x - 50 + 'px');
        $("#marker-name").html(this.subType);
        $("#marker-type").show();
    };

    this.getSubmitData = function () {
        return {
            x: this.x,
            y: this.y,
            diameter: this.diameter,
            submit_time: this.lastChangeTime,
            owner: this.owner,
            machine_id: this.machineId,
            type: this.type,
            sub_type: this.subType
        };
    }
}

Mark.createMark = function (type, x, y, diameter, appInterface) {
    switch (type) {
        case 'rock':
            return new Rock(x, y, appInterface);
        case 'transient':
            return new Transient(x, y, appInterface);
        case 'crater':
            return new Crater(x, y, diameter, appInterface);
        case 'X':
            return new XMark(x, y, diameter, appInterface);
        case 'checkmark':
            return new CheckMark(x, y, diameter, appInterface);
        case 'boulder':
            return new Segment(x, y, "boulder", appInterface);
    }
};