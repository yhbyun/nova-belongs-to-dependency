<script>
    export default {
        data: () => ({
            dependsOnValue: null,
        }),

        created() {
            if(this.field.dependsOn !== undefined) {
                this.registerDependencyWatchers(this.$root)
            }
        },

        methods: {
            registerDependencyWatchers(root) {
                root.$children.forEach(component => {
                    if (this.componentIsDependency(component)) {
                        if( component.selectedResourceId !== undefined ) {
                            // BelongsTo field
                            component.$watch('selectedResource', this.dependencyWatcher, {immediate: true})
                            this.dependencyWatcher(component.selectedResource)
                        }
                        else if( component.value !== undefined ) {
                            // Text based fields
                            component.$watch('value', this.dependencyWatcher, {immediate: true})
                            this.dependencyWatcher(component.value)
                        }
                    }

                    this.registerDependencyWatchers(component)
                })
            },
            componentIsDependency(component) {
                if(component.field === undefined) {
                    return false
                }

                return component.field.attribute === this.field.dependsOn
            },
            dependencyWatcher(select) {
                let value = select ? select.value : select
                if(value === this.dependsOnValue) {
                    return
                }

                this.dependsOnValue = value

                this.clearSelection()
                setTimeout(() => {
                    this.initializeComponent()
				}, 300);
            },
        },

        computed: {
            queryParams() {
                return {
                    params: {
                        current: this.selectedResourceId,
                        first: this.initializingWithExistingResource,
                        search: this.search,
                        withTrashed: this.withTrashed,
                        dependsOnValue: this.dependsOnValue,
                    },
                }
            },
        },
    }
</script>
