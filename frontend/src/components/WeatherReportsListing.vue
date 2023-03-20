<script>
import { useUsersStore } from "@/stores/usersStore";
import { computed, reactive, ref } from "vue";
import axios from "axios";
import WeatherDataCard from "./WeatherDataCard.vue";

export default {
  components: { WeatherDataCard },
  setup() {
    const usersStore = useUsersStore();
    const showDialog = ref(false);
    const loadingUserWeatherData = ref(false);

    const user = ref(null);
    let interval = ref({});
    const value = ref(0);

    const startLoader = () => {
      interval = setInterval(() => {
        if (this.value === 100) {
          return (this.value = 0);
        }
        this.value += 10;
      }, 1000);
    };

    const updateWeatherData = (userId) => {};

    const openDialog = (userId) => {
      console.log(userId);
      showDialog.value = true;

      loadingUserWeatherData.value = true;

      axios
        .get("http://localhost:8080/users/" + userId)
        .then((response) => {
          user.value = response.data.user;
          loadingUserWeatherData.value = false;

          // startLoader();
        })
        .catch((err) => {});
    };

    usersStore.getUsers();

    return {
      users: computed(() => usersStore.users),
      user,
      isLoading: computed(() => usersStore.isLoading),
      isFirstVisit: computed(() => usersStore.isFirstVisit),
      openDialog,
      showDialog,
      value: 50,
      loadingUserWeatherData,
    };
  },
};
</script>
<template>
  <div>
    <div class="text-center" v-show="isFirstVisit">
      <v-overlay v-model="overlay"></v-overlay>
    </div>
    <div class="text-center">
      <v-progress-circular
        indeterminate
        color="primary"
        v-show="isLoading == true"
      ></v-progress-circular>
    </div>
    <v-fade-transition>
      <v-row v-if="isLoading == false">
        <v-col cols="12" md="4" v-for="user in users" :key="user.id">
          <weather-data-card :user="user" @view-full-details="openDialog">
          </weather-data-card>
        </v-col>
      </v-row>
    </v-fade-transition>

    <v-row justify="center">
      <v-dialog
        v-model="showDialog"
        fullscreen
        :scrim="false"
        transition="dialog-bottom-transition"
      >
        <v-card>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click="showDialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Settings</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn variant="text" @click="showDialog = false"> Update </v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-container>
            <div class="text-center">
              <v-progress-circular
                indeterminate
                color="primary"
                v-show="loadingUserWeatherData == true"
              ></v-progress-circular>
              <v-slide-y-transition>
                <v-col v-if="loadingUserWeatherData == false" class="my-5">
                  <h2>
                    Lon: {{ user.current_weather.coord.lon }}
                    <br />
                    Lat:
                    {{ user.current_weather.coord.lat }}
                  </h2>
                  <h3>
                    {{
                      new Date(
                        user.current_weather.dt * 1000
                      ).toLocaleDateString()
                    }}
                  </h3>

                  <v-row justify="center">
                    <v-col class="" md="3" cols="12">
                      <div>
                        <h1>{{ user.current_weather.main.temp }}&deg;C</h1>
                        <p>{{ user.current_weather.weather[0].description }}</p>
                        <p>
                          Last Updated at :
                          {{
                            new Date(
                              user.current_weather.dt * 1000
                            ).toLocaleTimeString()
                          }}
                        </p>
                      </div>
                    </v-col>
                    <v-col md="3" cols="12">
                      <v-avatar
                        class="elevation-3"
                        v-if="user.current_weather"
                        size="130"
                        :image="
                          'http://openweathermap.org/img/wn/' +
                          user.current_weather.weather[0].icon +
                          '@2x.png'
                        "
                      >
                      </v-avatar>
                    </v-col>
                    <!-- <v-col cols="5" class="text-left">
                  <v-avatar
                    v-if="user.current_weather"
                    size="140"
                    :image="
                      'http://openweathermap.org/img/wn/' +
                      user.current_weather.weather[0].icon +
                      '@2x.png'
                    "
                  >
                  </v-avatar>
                </v-col> -->
                  </v-row>
                  <v-row justify="center">
                    <v-col md="3" sm="4" cols="12">
                      Feels Like:
                      {{ user.current_weather.main.feels_like }}&deg;C
                    </v-col>
                    <v-col md="3" sm="4" cols="12">
                      Cloudiness: {{ user.current_weather.clouds.all }}%
                    </v-col>
                    <v-col md="3" sm="4" cols="12">
                      Wind Speed: {{ user.current_weather.wind.speed }}m/s
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                      <v-progress-circular
                        :rotate="360"
                        :size="100"
                        :width="15"
                        :model-value="value"
                        color="teal"
                      >
                        {{ value }} %
                      </v-progress-circular>
                    </v-col>
                    <v-col>
                      <v-progress-circular
                        :rotate="360"
                        :size="100"
                        :width="15"
                        :model-value="user.current_weather.main.humidity"
                        color="teal"
                      >
                        {{ user.current_weather.main.humidity }} %
                      </v-progress-circular>
                      <h3>Humidity</h3>
                    </v-col>
                    <v-col>
                      <v-progress-circular
                        :rotate="360"
                        :size="100"
                        :width="15"
                        :model-value="value"
                        color="teal"
                      >
                        {{ value }} %
                      </v-progress-circular>
                    </v-col>
                  </v-row>
                </v-col>
              </v-slide-y-transition>
            </div>
          </v-container>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>