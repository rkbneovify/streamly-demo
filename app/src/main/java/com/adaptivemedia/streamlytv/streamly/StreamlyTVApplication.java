package com.adaptivemedia.streamlytv.streamly;

import android.app.Application;
import android.content.Context;

import androidx.lifecycle.Lifecycle;
import androidx.lifecycle.LifecycleObserver;
import androidx.lifecycle.OnLifecycleEvent;
import androidx.lifecycle.ProcessLifecycleOwner;

public class StreamlyTVApplication extends Application{
        public static StreamlyTVApplication application;

        public StreamlyTVApplication() {
            application = this;
        }

        @Override
        public void onCreate() {
            super.onCreate();
            application = this;
//        AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
        }

        @Override
        public void onTerminate() {
            super.onTerminate();
        }

        public static StreamlyTVApplication getApp() {
            if (application == null) {
                application = new StreamlyTVApplication();
            }
            return application;
        }

        public static Context getAppContext() {
            if (application == null) {
                application = new StreamlyTVApplication();
            }
            return application;
        }
}