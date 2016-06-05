premium = {

    /**
     * Show save button
     */
    show_save_button    : function (cnv_id) {

        return this.get_template('console-user-tools-premium', {
            obj_user_data: cnv_id,
            button_text  : this.strings.msg.save_chat
        });

    },
    /**
     * Show chat elapsed time
     */
    show_chat_timer     : function (cnv) {

        var self = this;

        $('#YLC_sidebar_right').append(self.get_template('console-user-timer-premium', {
            timer_title: self.strings.msg.timer
        }));

        self.data.ref_cnv.child(cnv).once('value', function (snapshot) {

            if (snapshot.val() !== null) {

                var cnv_obj = snapshot.val();

                if (cnv_obj.accepted_at == '') {

                    self.data.ref_cnv.child(cnv).child('accepted_at').set(Firebase.ServerValue.TIMESTAMP, function () {

                        self.data.ref_cnv.child(cnv).once('value', function (snapshot) {

                            if (snapshot.val() !== null) {

                                var cnv_obj = snapshot.val();
                                self.trigger_premium('set_timer', cnv_obj.accepted_at);
                            }

                        });

                    });

                } else {

                    self.trigger_premium('set_timer', cnv_obj.accepted_at);

                }

            }

        });

    },
    /**
     * Set Conversation timer
     */
    set_timer           : function (start) {
        var self = this;

        self.objs.list_interval = setInterval(function () {
            var now = new Date(),
                time = self.trigger_premium('chat_duration', start, now.getTime());

            $('#YLC_timer').html(time);

            if ($('#YLC_cnv_reply').is(':disabled')) {
                clearInterval(self.objs.list_interval);
            }

        }, 1000);

    },
    /**
     * End chat from console
     */
    end_chat_console    : function (cnv_id, delete_from_app, end_chat, btn, ntf) {

        var self = this;

        this.trigger_premium('save_user_data', cnv_id, delete_from_app, end_chat, delete_from_app, function (r) {

            self.objs.working = false;

            btn.removeClass('button-disabled');

            if (r.error)
                ntf.html(r.error);
            else
                ntf.html(r.msg);

            setTimeout(function () {

                ntf.fadeOut(2000);

            }, 1000);

            if (delete_from_app) {

                setTimeout(function () {

                    self.show_welcome_popup();

                }, 3000);

            }

        });

    },
    /**
     * Play sound
     */
    play_sound          : function (sound_name) {

        // Add source into <audio> tag
        function add_source(e, path) {
            $('<source>').attr('src', path).appendTo(e);
        }

        var audio = $('<audio />', {
            autoPlay: 'autoplay'
        });

        add_source(audio, ylc.plugin_url + '/sounds/' + sound_name + '.mp3');
        add_source(audio, ylc.plugin_url + '/sounds/' + sound_name + '.ogg');
        add_source(audio, ylc.plugin_url + '/sounds/' + sound_name + '.wav');

        audio.appendTo('body');
    },
    /**
     * Show offline Message form
     */
    show_offline_form   : function () {

        var self = this,
            working = false;

        // Send offline form

        self.objs.popup_body.append(self.get_template('chat-offline-form-premium', {
            btn_text     : self.strings.msg.send_btn,
            btn_styles   : ( ylc.is_premium ) ? self.trigger_premium('stylize', 'form_button') : '',
            field_name   : self.strings.fields.name,
            name_ph      : self.strings.fields.name_ph,
            field_email  : self.strings.fields.email,
            email_ph     : self.strings.fields.email_ph,
            field_message: self.strings.fields.message,
            message_ph   : self.strings.fields.message_ph
        }));

        $(document).off('click', '#YLC_send_btn');
        $(document).on('click', '#YLC_send_btn', function (e) {

            if (working) return false; // Don't allow to send form twice!

            // Display "sending" message
            self.display_ntf(self.strings.msg.sending + '...', 'sending ');

            // Get login form data
            var form_data = $('#YLC_popup_form').serializeArray(),
                form_length = form_data.length - 1;

            $.each(form_data, function (i, f) {

                // Update current form data
                self.data.current_form[f.name] = f.value;

                // Is empty?
                if (!f.value) {
                    self.display_ntf(self.strings.msg.field_empty, 'error');
                    return false;
                }

                // Is valid email?
                if (f.name === 'email') {

                    // Invalid email!
                    if (!self.validate_email(f.value)) {
                        self.display_ntf(self.strings.msg.invalid_email, 'error');
                        return false;
                    }

                } else if (f.name === 'name') {

                    if (!self.validate_username(f.value)) {
                        self.display_ntf(self.strings.msg.invalid_username, 'error');
                        return false;
                    }

                }

                if (i === form_length) {

                    working = true;

                    var send_data = $('#YLC_popup_form').serialize() + '&vendor_id=' + ylc.active_vendor.vendor_id;

                    self.post('ylc_ajax_callback', 'offline_form', send_data, function (r) {

                        working = false;

                        if (r.error) {

                            self.display_ntf(r.error, 'error'); // Display error message

                        } else if (r.warn) {

                            self.display_ntf(r.warn, 'success'); // Display message

                            setTimeout(function () {

                                self.clean_ntf(); // Clean display message

                                self.minimize(); // Minimize popup

                            }, 4000);

                        } else {

                            self.display_ntf(r.msg, 'success'); // Display message

                            setTimeout(function () {

                                self.clean_ntf(); // Clean display message

                                self.minimize(); // Minimize popup

                            }, 2000);
                        }

                    });

                }

            });

            return false;

        });

        $(document).on('mouseenter', '#YLC_send_btn', function () {
            $(this).css('background-color', self.data.primary_hover);
        });
        $(document).on('mouseleave', '#YLC_send_btn', function () {
            $(this).css('background-color', self.opts.styles.bg_color);
        });

    },
    /**
     * End chat options
     */
    end_chat_options    : function (end_chat) {

        if (end_chat) {
            var now = new Date();
            this.trigger_premium('save_user_data', this.data.user.conversation_id, true, now.getTime(), true);
        }

        if (ylc.chat_evaluation) {

            this.trigger_premium('show_chat_evaluation');

        } else {

            if (ylc.send_transcript && this.data.user.user_email != '') {

                this.trigger_premium('show_copy_request');

            }

        }

    },
    /**
     * Show Chat evaluation
     */
    show_chat_evaluation: function () {

        var self = this,
            working = false;

        self.objs.popup_body.append(self.get_template('chat-evaluation-premium', {
            eval_text : self.strings.msg.chat_evaluation,
            good_text : self.strings.msg.good,
            bad_text  : self.strings.msg.bad,
            transcript: ( !ylc.send_transcript || self.data.user.user_email == '' ) ? '' : self.get_template('chat-transcript-premium', {
                chat_copy: self.strings.msg.chat_copy
            })
        }));

        $(document).off('click', '#YLC_good_btn, #YLC_bad_btn');
        $(document).on('click', '#YLC_good_btn, #YLC_bad_btn', function (e) {

            if (working) return false; // Don't allow to send form twice!

            working = true;

            self.display_ntf(self.strings.msg.sending + '...', 'sending');

            var evaluation = ( $(this).attr('id') === 'YLC_good_btn' ) ? 'good' : 'bad',
                receive_copy = $('#YLC_request_chat').is(':checked') ? 1 : 0;

            self.post('ylc_ajax_callback', 'chat_evaluation', {
                conversation_id: self.data.user.conversation_id,
                evaluation     : evaluation,
                receive_copy   : receive_copy,
                user_email     : self.data.user.user_email,
                chat_with      : self.data.user.chat_with
            });

            self.minimize();

            return false;

        });

    },
    /**
     * Show copy request only
     */
    show_copy_request   : function () {

        var self = this,
            working = false;

        self.objs.popup_body.append(self.get_template('chat-transcript-btn-premium', {
                chat_copy: self.strings.msg.chat_copy
            })
        );

        $(document).off('click', '#YLC_chat_request');
        $(document).on('click', '#YLC_chat_request', function (e) {

            if (working) return false; // Don't allow to send form twice!

            working = true;

            self.display_ntf(self.strings.msg.sending + '...', 'sending');

            var evaluation = '',
                receive_copy = 1;

            self.post('ylc_ajax_callback', 'chat_evaluation', {
                conversation_id: self.data.user.conversation_id,
                evaluation     : evaluation,
                receive_copy   : receive_copy,
                user_email     : self.data.user.user_email,
                chat_with      : self.data.user.chat_with
            });

            self.minimize();

            return false;

        });

    },
    /**
     * Calculates Chat Duration
     */
    chat_duration       : function (start_time, now_time) {

        if (now_time == '' || start_time == '') {
            return '00:00:00'
        }

        var seconds = ( ( now_time - start_time ) * 0.001 ) >> 0,
            minutes = seconds / 60 >> 0,
            hours = minutes / 60 >> 0;

        hours = hours % 60;
        minutes = minutes % 60;
        seconds = seconds % 60;

        hours = ( hours < 10 ) ? '0' + hours : hours;
        minutes = ( minutes < 10 ) ? '0' + minutes : minutes;
        seconds = ( seconds < 10 ) ? '0' + seconds : seconds;

        return hours + ':' + minutes + ':' + seconds;

    },
    /**
     * Save user data into DB
     */
    save_user_data      : function (cnv_id, delete_from_app, end_chat, send_email, callback) {

        var self = this,
            r = null;

        this.data.ref_cnv.child(cnv_id).once('value', function (snap_cnv) {

            var exists = ( snap_cnv.val() !== null ),
                cnv = snap_cnv.val();

            if (!exists) {

                if (callback) {

                    callback({});

                }

                return;
            }

            var user_id = cnv.user_id,
                duration = self.trigger_premium('chat_duration', cnv.accepted_at, end_chat);

            self.data.ref_users.child(user_id).once('value', function (snap_user) {

                var user_data = snap_user.val();

                user_data.created_at = cnv.created_at;
                user_data.evaluation = cnv.evaluation;
                user_data.duration = duration;
                user_data.receive_copy = cnv.receive_copy;
                user_data.send_email = send_email;

                self.data.ref_msgs.once('value', function (snap_msgs) {

                    var msgs = snap_msgs.val(),
                        total_msgs = msgs ? Object.keys(msgs).length : 0,
                        i = 0,
                        msgs_data = {};

                    if (msgs) {

                        $.each(msgs, function (msg_id, msg) {

                            i = i + 1;

                            if (msg.conversation_id === cnv_id) {

                                msgs_data[msg_id] = msg;

                                if (delete_from_app)
                                    self.data.ref_msgs.child(msg_id).remove();

                            }

                            if (total_msgs === i) {

                                user_data.msgs = msgs_data;

                                self.post('ylc_ajax_callback', 'save_chat', user_data, function (r) {

                                    if (callback)
                                        callback(r);

                                });

                            }

                        });

                    } else if (callback) {
                        callback({});
                    }

                    if (delete_from_app) {

                        self.data.ref_users.child(user_id).remove();
                        self.data.ref_cnv.child(cnv_id).remove();
                    }

                });

            });

        });

    },
    /**
     * Gravatar
     */
    set_avatar_premium  : function (user_type, user_data) {

        if (user_type != 'admin')
            return 'https://www.gravatar.com/avatar/' + user_data.gravatar + '.jpg?s=60&d=' + ylc.default_user_avatar;

        switch (user_data.avatar_type) {

            case 'gravatar':
                return 'https://www.gravatar.com/avatar/' + user_data.gravatar + '.jpg?s=60&d=' + ylc.default_admin_avatar;
                break;

            case 'image':
                return user_data.avatar_image;
                break;

            default:

                if (ylc.company_avatar != '') {

                    return ylc.company_avatar;

                } else {

                    return this.data.assets_url + '/images/default-avatar-' + user_type + '.png';

                }

        }

    },
    /**
     * Get custom styles
     */
    stylize             : function (element) {

        var styles = [];

        switch (element) {

            case 'chat_button' :

                styles = [
                    'color: ' + this.data.primary_fg,
                    'background-color: ' + this.opts.styles.bg_color,
                    'width:' + this.opts.styles.btn_width + 'px',
                    '-webkit-border-radius:' + this.opts.styles.border_radius,
                    '-moz-border-radius:' + this.opts.styles.border_radius,
                    'border-radius:' + this.opts.styles.border_radius,
                    this.opts.styles.x_pos + ': 40px; ' + ( ( this.opts.styles.x_pos === 'right' ) ? 'left: auto' : 'right: auto' ),
                    this.opts.styles.y_pos + ': 0; ' + ( ( this.opts.styles.y_pos === 'top' ) ? 'bottom: auto' : 'top: auto' )
                ];

                break;

            case 'chat_widget' :

                styles = [
                    this.opts.styles.x_pos + ': 40px; ' + ( ( this.opts.styles.x_pos === 'right' ) ? 'left: auto' : 'right: auto' ),
                    this.opts.styles.y_pos + ': 0; ' + ( ( this.opts.styles.y_pos === 'top' ) ? 'bottom: auto' : 'top: auto' ),
                    '-webkit-border-radius:' + this.opts.styles.border_radius,
                    '-moz-border-radius:' + this.opts.styles.border_radius,
                    'border-radius:' + this.opts.styles.border_radius
                ];

                break;

            case 'chat_header' :

                styles = [
                    'color: ' + this.data.primary_fg,
                    'background-color: ' + this.opts.styles.bg_color
                ];

                break;

            case 'form_button' :

                styles = [
                    'color: ' + this.data.primary_fg,
                    'background-color: ' + this.opts.styles.bg_color
                ];

                break;

            case 'chat_body' :

                styles = [
                    '-webkit-border-radius:' + this.opts.styles.border_radius,
                    '-moz-border-radius:' + this.opts.styles.border_radius,
                    'border-radius:' + this.opts.styles.border_radius
                ];

                break;

            default :
                styles = [];

        }

        return styles.join('; ');

    },
    /**
     * Rezize chat window
     */
    resize_chat         : function () {
        var win_w = $(window).width();

        if (win_w > 480) {

            this.objs.btn.css({
                'width': this.opts.styles.btn_width + 'px',
                'left' : ( this.opts.styles.x_pos === 'right' ) ? 'auto' : '40px',
                'right': ( this.opts.styles.x_pos === 'left' ) ? 'auto' : '40px'
            });

            this.objs.popup.css({
                'left' : ( this.opts.styles.x_pos === 'right' ) ? 'auto' : '40px',
                'right': ( this.opts.styles.x_pos === 'left' ) ? 'auto' : '40px'
            });

            $('.chat-body.chat-online').css('width', this.opts.styles.popup_width + 'px');

            $('.chat-body.chat-form').css('width', this.opts.styles.form_width + 'px');

        } else {

            this.objs.btn.css({
                'width': '',
                'left' : ( this.opts.styles.x_pos === 'right' ) ? 'auto' : 0,
                'right': ( this.opts.styles.x_pos === 'left' ) ? 'auto' : 0
            });

            this.objs.popup.css({
                'left' : ( this.opts.styles.x_pos === 'right' ) ? 'auto' : 0,
                'right': ( this.opts.styles.x_pos === 'left' ) ? 'auto' : 0
            });

            this.objs.popup_body.css('width', '');

        }


    },
    /**
     * Logged automatically authenticated
     */
    logged_users_auth   : function () {

        if (this.opts.user_info.user_name != '' && this.opts.user_info.user_email != '') {

            this.data.current_form = {
                user_name : this.opts.user_info.user_name,
                user_email: this.opts.user_info.user_email,
                gravatar  : this.md5(this.opts.user_info.user_email)
            };

            this.opts.display_login = false;

        }

    }
    /**
     * Autoplay
     */
    /*autoplay                : function () {

     if ( this.opts.user_info.user_name != '' && this.opts.user_info.user_email != '' ) {

     this.data.current_form = {
     user_name   : this.opts.user_info.user_name,
     user_email  : this.opts.user_info.user_email,
     gravatar 	: this.md5( this.opts.user_info.user_email )
     };

     } else {

     this.data.current_form = {
     gravatar 	: '0'
     };

     }

     this.opts.display_login = false;

     var obj_btn         = this.objs.btn,
     obj_btn_title   = this.objs.btn.find( '.chat-title'),
     self            = this;

     this.auth( function( wait ) {

     if ( ! wait ){

     obj_btn.hide();

     // Show popup
     self.show_popup();

     // Update title
     obj_btn_title.html( self.strings.msg.chat_title );

     }

     });

     },*/
    /**
     *  Autoplay Message
     */
    /*autoplay_msg            : function ( cnv_id ) {

     this.data.ref_msgs.push({
     user_id		    : 'ylc-op-auto',
     user_type	    : 'operator',
     conversation_id : cnv_id,
     user_name 		: ylc.autoplay_opts.company_name,
     gravatar 	    : '',
     avatar_type     : 'default',
     avatar_image    : '',
     msg 		    : ylc.autoplay_opts.auto_msg,
     msg_time 		: Firebase.ServerValue.TIMESTAMP
     });

     }*/
};