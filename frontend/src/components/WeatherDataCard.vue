<script>
export default {
  emits: ["view-full-details"],
  props: {
    user: Object,
  },
};
</script>
<template>
  <div>
    <!-- <div> -->

    <v-card class="mx-auto" width="400">
      <v-card-item :title="user.name"> </v-card-item>
      <v-divider> </v-divider>

      <v-card-text class="py-0">
        <div>
          <!-- <v-icon size="18" color="error" class="me-1 pb-1">
              mdi-alert
            </v-icon> -->

          Lon: {{ user.current_weather.coord.lon }}
          <br />
          Lat:
          {{ user.current_weather.coord.lat }}
        </div>
        <v-row align="center" no-gutters>
          <v-col cols="7">
            <h3 class="text-h3">{{ user.current_weather.main.temp }}&deg;C</h3>
            <p>
              <!-- <v-icon size="18" color="error" class="me-1 pb-1">
                  mdi-alert
                </v-icon> -->

              {{ user.current_weather.weather[0].description }}
            </p>
          </v-col>

          <v-col cols="5" class="text-right">
            <v-avatar
              v-if="user.current_weather"
              size="140"
              :image="
                'http://openweathermap.org/img/wn/' +
                user.current_weather.weather[0].icon +
                '@2x.png'
              "
            >
              <!-- <v-img :src="'http://openweathermap.org/img/wn/10d@2x.png'">
                </v-img> -->
            </v-avatar>
            <!-- <v-icon
                color="error"
                icon="mdi-weather-hurricane"
                size="88"
              ></v-icon> -->
          </v-col>
        </v-row>
      </v-card-text>

      <div class="d-flex py-3 justify-space-between">
        <v-list-item density="compact" prepend-icon="mdi-weather-windy">
          <v-list-item-subtitle
            >{{ user.current_weather.wind.speed }} km/h</v-list-item-subtitle
          >
        </v-list-item>

        <v-list-item density="compact" prepend-icon="mdi-weather-pouring">
          <v-list-item-subtitle>48%</v-list-item-subtitle>
        </v-list-item>
      </div>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="$emit('view-full-details', user.id)" outlined>
          full data
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>