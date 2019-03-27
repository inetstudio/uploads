+function ($) {
    "use strict";

    var isIE = window.navigator.appName == 'Microsoft Internet Explorer';

    // FILEUPLOAD PUBLIC CLASS DEFINITION
    // =================================

    var Fileinput = function (element, options) {
        this.$element = $(element);

        this.options = $.extend({}, Fileinput.DEFAULTS, options);
        this.$input = this.$element.find(':file');
        if (this.$input.length === 0) return;

        this.name = this.$input.attr('name') || options.name;

        this.$hidden = this.$element.find('input[type=hidden][name="' + this.name + '"]');
        if (this.$hidden.length === 0) {
            this.$hidden = $('<input type="hidden">').insertBefore(this.$input);
        }

        this.original = {
            exists: this.$element.hasClass('fileinput-exists'),
            hiddenVal: this.$hidden.val()
        };

        this.listen();
        this.reset();
    };

    Fileinput.DEFAULTS = {
        clearName: true
    };

    Fileinput.prototype.listen = function () {
        this.$input.on('change.bs.fileinput', $.proxy(this.change, this));
        $(this.$input[0].form).on('reset.bs.fileinput', $.proxy(this.reset, this));

        this.$element.find('[data-trigger="fileinput"]').on('click.bs.fileinput', $.proxy(this.trigger, this));
        this.$element.find('[data-dismiss="fileinput"]').on('click.bs.fileinput', $.proxy(this.clear, this));
    };

    Fileinput.prototype.verifySizes = function (files) {
        if (typeof this.options.maxSize === 'undefined') return true;

        var max = parseFloat(this.options.maxSize);
        if (max !== this.options.maxSize) return true;

        for (var i = 0; i < files.length; i++) {
            var size = typeof files[i].size !== 'undefined' ? files[i].size : null;
            if (size === null) continue;

            size = size / 1000 / 1000 /* convert from bytes to MB */
            if (size > max) return false;
        }

        return true;
    };

    Fileinput.prototype.change = function (e) {
        var files = e.target.files === undefined ? (e.target && e.target.value ? [{name: e.target.value.replace(/^.+\\/, '')}] : []) : e.target.files

        e.stopPropagation();

        if (files.length === 0) {
            this.clear();
            this.$element.trigger('clear.bs.fileinput');
            return;
        }

        if (!this.verifySizes(files)) {
            this.$element.trigger('max_size.bs.fileinput');

            this.clear();
            this.$element.trigger('clear.bs.fileinput');
            return;
        }

        this.$hidden.val('');
        this.$hidden.attr('name', '');
        this.$input.attr('name', this.name);

        var file = files[0];

        var text = file.name;
        var $nameView = this.$element.find('.fileinput-filename');

        if (files.length > 1) {
            text = $.map(files, function (file) {
                return file.name;
            }).join(', ');
        }

        $nameView.text(text);
        this.$element.addClass('fileinput-exists').removeClass('fileinput-new');
        this.$element.trigger('change.bs.fileinput');
    };

    Fileinput.prototype.clear = function (e) {
        if (e) e.preventDefault();

        this.$hidden.val('');
        this.$hidden.attr('name', this.name);
        if (this.options.clearName) this.$input.attr('name', '');

        //ie8+ doesn't support changing the value of input with type=file so clone instead
        if (isIE) {
            var inputClone = this.$input.clone(true);
            this.$input.after(inputClone);
            this.$input.remove();
            this.$input = inputClone;
        } else {
            this.$input.val('');
        }

        this.$element.find('.fileinput-filename').text('');
        this.$element.addClass('fileinput-new').removeClass('fileinput-exists');

        if (e !== undefined) {
            this.$input.trigger('change');
            this.$element.trigger('clear.bs.fileinput');
        }
    };

    Fileinput.prototype.reset = function () {
        this.clear();

        this.$hidden.val(this.original.hiddenVal);
        this.$element.find('.fileinput-filename').text('');

        if (this.original.exists) this.$element.addClass('fileinput-exists').removeClass('fileinput-new');
        else this.$element.addClass('fileinput-new').removeClass('fileinput-exists');

        this.$element.trigger('reseted.bs.fileinput');
    };

    Fileinput.prototype.trigger = function (e) {
        this.$input.trigger('click');
        e.preventDefault();
    };


    // FILEUPLOAD PLUGIN DEFINITION
    // ===========================

    var old = $.fn.fileinput;

    $.fn.fileinput = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('bs.fileinput');
            if (!data) $this.data('bs.fileinput', (data = new Fileinput(this, options)));
            if (typeof options == 'string') data[options]();
        })
    }

    $.fn.fileinput.Constructor = Fileinput;


    // FILEINPUT NO CONFLICT
    // ====================

    $.fn.fileinput.noConflict = function () {
        $.fn.fileinput = old;
        return this;
    };


    // FILEUPLOAD DATA-API
    // ==================

    $(document).on('click.fileinput.data-api', '[data-provides="fileinput"]', function (e) {
        var $this = $(this);
        if ($this.data('bs.fileinput')) return;
        $this.fileinput($this.data());

        var $target = $(e.target).closest('[data-dismiss="fileinput"],[data-trigger="fileinput"]');
        if ($target.length > 0) {
            e.preventDefault();
            $target.trigger('click.bs.fileinput');
        }
    });

}(window.jQuery);
