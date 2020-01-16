import Vue from 'vue';
import moment from 'moment-timezone';

window.$(document).ready(() => {
  var count = window.$("#item0").length;
  console.log(moment.tz.guess());
  window.$('#addButton').click(() => {
    var timezone = moment.tz.guess();
    let codeEntry = `<input type="hidden" name="codeBox[]" value="${count}">
        <label for="codeInput" class="textLabel">Code</label>
        <div class="input-group">
          <input type="text" class="form-control" id="codeInput${count}" name="code[]" placeholder="Code..." required>
          <input type='hidden' value="${timezone}" name="timezone[]"/>
          <span class="input-group-addon">
            <input type="checkbox" name="random[]" id="randomBox${count}" value="${count}">Random Code</input>
          </span>
        </div>
        <div class="form-group">
        <p class="help-block">The Unlock Code to be entered by users.
        Selecting random creates an unlock code of 6 characters that will be
        viewable after the achievment has been created.</p>
        </div>
        <div class="form-group">
                <div class='input-group date' id='datetimepicker${count}'>
                  <datetime name="date[]" empty="true"></datetime>
                </div>
          <p class="help-block">The expiration date for the unlock code. After
          the Expiration date has passed users will be unable to use it. The
          Expiration date is currently in the ${moment.tz.guess()} timezone. Leaving the Expiration
          date emtpy means the Achievment is always available. </p>
        </div>
        <div class="input-group">
          <button type="button" class="dellbutton form-control btn btn-danger"
          id="dellbutton${count}">Delete Join Code</button>
        </div>`;

    window.$("#codeList").append(`<li id="item${count}"><div class="well well-sm" name="code${count}">${codeEntry}</div></li>`);

    window.$(`#dellbutton${count}`).click(function() {
      window.$(this).parent().parent().parent().remove();
    });

    window.$(`#randomBox${count}`).change(function() {
      window.$(this).parent().siblings().first().prop('disabled', function(i, v) { return !v;});
      window.$(this).parent().siblings().first().prop('required', function(i, v) { return !v;});
    });
    new Vue({
      el: `#datetimepicker${count}`,
      components: {
        datetime: require('./components/DateTimePicker.vue')
      }
    });
    count++;
  });

  if(window.$('#addButton').length == 0) {
    new Vue({
      el: `#datetimepicker`,
      components: {
        datetime: require('./components/DateTimePicker.vue')
      }
    });
  }
});
