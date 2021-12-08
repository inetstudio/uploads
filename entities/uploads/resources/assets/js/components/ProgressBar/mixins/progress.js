export default {
  data: function () {
    return {
      progress: {
        state: false,
        percents: 0,
        text: '',
        style: {
          width: '0%'
        },
        reset: function () {
          this.state = false;
          this.percents = 0;
          this.text = '';
          this.style.width = '0%';
        }
      }
    }
  }
};
