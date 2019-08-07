<?php ?>
<HTML>
<HEAD>
    <TITLE>
        Add three numbers
    </TITLE>
    <SCRIPT>
        function addThreeNums (inOne, inTwo, inThree) {

            return Number(inOne) + Number(inTwo) + inThree;
        }
    </SCRIPT>
</HEAD>
<BODY>
<FORM Name="theForm">
    <INPUT Type=Text Name="num1">
    <INPUT Type=Text Name="num2">
    <INPUT Type=Text Name="num3">
    <INPUT Type=Button Value="Add Them"
           onClick='document.write("sum:" +addThreeNums(theForm.num1.value,theForm.num2.value,theForm.num3.value));'>
</FORM>
</BODY>
</HTML>

