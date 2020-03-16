function TutorialStep(image, correctMarks, goalText, startingHelpText, activeTools, isLiveHelpActive, correctCountNeeded) {
    this.image = image;
    this.currentTextIndex = 0;
    this.correctMarks = correctMarks;
    this.goalText = goalText;
    this.startingHelpText = startingHelpText;
    this.activeTools = activeTools;
    this.isLiveHelpActive = isLiveHelpActive;
    this.correctCountNeeded = correctCountNeeded;
}