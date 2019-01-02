<template>

    <div class="content-item">
        <div class="content-header" @click="showText = !showText" :class="status">
            <div class="content-title">
                <div class="expander-icon" :class="showText ? 'expanded' : ''">
                    <i class="fas fa-angle-down"></i>
                </div>
                <div>{{ content.header }}</div>
            </div>
            <div class="content-status">
                
                <transition name="saving" mode="in-out">
                    <div v-show="saving" class="content-status-icon saving-icon"><i class="fas fa-save"></i></div>
                </transition>

                <div v-show="status == 'published'" class="content-status-icon published"><i class="fas fa-check"></i></div>

                <div v-show="status == 'changed'" class="content-status-icon changed">
                    <div v-if="canPublish" class="button" @click.stop.prevent="publish()">Publish</div>
                    <div v-else>Changed</div>
                </div>

                <div class="content-remove-icon" v-if="canPublish" @click.stop.prevent="remove()"><i class="fas fa-times"></i></div>

            </div>
        </div>

        <transition name="expander">
            <div class="content-bodytext" v-if="showText">
                <editor api-key="cnqsdiwqe98zykav18rvw2dqp0ykt34jhuvg268m11w0ax1c" 
                    v-model="content.bodytext"
                    :init="{ height: 350 }"
                ></editor>
            </div>
        </transition>
    </div>


</template>

<script>
    export default {

        props: ['content', 'canPublish'],

        data: function() {
        
            return {
                saving: false,
                showText: false,
            }
        
        },

        watch: {
        
            bodytext() {
                this.updateBodytext();
            }

        },

        computed: {

            bodytext() {
            
                return this.content.bodytext;

            },
        
            status() {
                return this.content.original_bodytext == this.content.bodytext ? 'published' : 'changed';
            }

        },

        methods: {

            publish: function() {
            
                this.saving = true;

                let post_data = {
                    'id': this.content.id
                }

                this.$http.post('/content/publish', post_data).then( response => {
                    this.content.original_bodytext = this.content.bodytext;
                    this.saved();
                }, error => {
                        
                });
            
            },

            remove: function() {

                let post_data = {
                    'id': this.content.id
                }
            
                var answer = confirm('Are you sure you want to delete this content item from the updater?');
                if (answer == true) {
                
                    this.$http.post('/content/remove', post_data).then( response => {
                        this.$emit('remove', this.content.id);
                    }, error => {
                            
                    });
                }
            },

            saved: function() {
                setTimeout( () => {
                    this.saving = false;
                }, 1000);
            },

            updateBodytext: _.debounce(
                function () {

                    this.saving = true;

                    let post_data = {
                        'id': this.content.id,
                        'bodytext': this.content.bodytext
                    }

                    this.$http.post('/content/update', post_data).then( response => {
                        this.saved();
                    }, error => {
                            
                    });

                }, 1000
            ),


        
        }

    }
</script>
