<template>

    <div class="content-items">

        <div class="section" v-if="canAddCourses">
            <div class="input-block">
                <div class="input-label">Add Content By External ID</div>
                <div class="input"><input v-model="addId" @keyup.enter.prevent.stop="addContentById"/></div>
            </div>
        </div>

        <div class="section">
            <content-item v-for="(content, index) in contentItems" :can-publish="canPublish" :content="content" :key="content.id" @remove="remove(content, index)"></content-item>
        </div>

    </div>


</template>

<script>
    export default {

        props: ['canPublish', 'canAddCourses'],

        data: function() {
        
            return {
                addId: '',
                contentItems: []
            }
        
        },

        mounted() {
        
            this.loadContent();


        },

        methods: {
        
            loadContent: function() {
            
                this.$http.post('/content/load').then( response => {
                    this.contentItems = response.data.contentItems;
                }, error => {
                    console.log('there was an error');
                });
            
            },


            addContentById: function() {
            
                this.$http.post('/content/create', {'id': this.addId}).then( response => {
                    this.addId = '';
                    this.loadContent();
                }, error => {
                    console.log('there was an error');
                });

            },

            remove: function(content, index) {
                this.contentItems.splice(index, 1);
            }
        
        }



    }
</script>
