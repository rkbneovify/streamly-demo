package com.adaptivemedia.streamlytv.streamly.search_channels;

import android.Manifest;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.os.Build;
import android.os.Bundle;
import android.speech.RecognitionListener;
import android.speech.RecognizerIntent;
import android.speech.SpeechRecognizer;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.epg.ChannellistModel;
import com.adaptivemedia.streamlytv.streamly.epg.Datum;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.receivers.NetworkStateReceiver;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SearchActivity extends FragmentActivity implements NetworkStateReceiver.NetworkStateReceiverListener {
    private static final String TAG = SearchActivity.class.getSimpleName();
    private EditText edSearch;
    private ImageView imgVoiceSearch;
    private ProgressBar progressBar;
    private RecyclerView rvSearch;
    String authToken;
    private static final int REQUEST_CODE = 1234;
    private static final int SPEECH_REQUEST_CODE = 0;

    List<SearchModel> searchList = new ArrayList<>();
    SearchAdapter searchAdapter;
    private LinearLayout llNoDataFound,layoutSearch,layoutInternet;
    NetworkStateReceiver networkStateReceiver;
    public static final Integer RecordAudioRequestCode = 1;
    private SpeechRecognizer speechRecognizer;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search);
        initViews();
        edSearch.setEnabled(false);
        if(ContextCompat.checkSelfPermission(this, Manifest.permission.RECORD_AUDIO) != PackageManager.PERMISSION_GRANTED){
            checkPermission();
        }
        speechRecognizer = SpeechRecognizer.createSpeechRecognizer(this);

        final Intent speechRecognizerIntent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        speechRecognizerIntent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        speechRecognizerIntent.putExtra(RecognizerIntent.EXTRA_LANGUAGE, Locale.getDefault());

        networkStateReceiver = new NetworkStateReceiver();
        networkStateReceiver.addListener(this);
        this.registerReceiver(networkStateReceiver, new IntentFilter(android.net.ConnectivityManager.CONNECTIVITY_ACTION));

        authToken = TokenStorage.readSharedToken(SearchActivity.this, ConstantUtility.AUTH_TOKEN, null);
        voiceSearch();

        edSearch.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                try {
                    searchAdapter.getFilter().filter(edSearch.getText().toString().replace(" ",""));

                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });


        speechRecognizer.setRecognitionListener(new RecognitionListener() {
            @Override
            public void onReadyForSpeech(Bundle bundle) {

            }

            @Override
            public void onBeginningOfSpeech() {
//                edSearch.setText("");
//                edSearch.setHint("Listening...");
                Toast.makeText(SearchActivity.this,"Listening...",Toast.LENGTH_SHORT).show();

            }

            @Override
            public void onRmsChanged(float v) {

            }

            @Override
            public void onBufferReceived(byte[] bytes) {

            }

            @Override
            public void onEndOfSpeech() {

            }

            @Override
            public void onError(int i) {

            }

            @Override
            public void onResults(Bundle bundle) {

                ArrayList<String> data = bundle.getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);
                edSearch.setText(data.get(0));
            }

            @Override
            public void onPartialResults(Bundle bundle) {

            }

            @Override
            public void onEvent(int i, Bundle bundle) {

            }
        });

        imgVoiceSearch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                speechRecognizer.startListening(speechRecognizerIntent);

            }
        });
    }


    private void checkPermission() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            ActivityCompat.requestPermissions(this,new String[]{Manifest.permission.RECORD_AUDIO},RecordAudioRequestCode);
        }
    }
    private void initViews(){
        llNoDataFound = findViewById(R.id.llNoDataFound);
        edSearch = findViewById(R.id.ed_search);
        imgVoiceSearch = findViewById(R.id.img_voice_search);
        progressBar = findViewById(R.id.progress_bar);
        layoutSearch = findViewById(R.id.layout_search);
        rvSearch = findViewById(R.id.rv_search);
        layoutInternet = findViewById(R.id.layout_internet);
    }

    private void voiceSearch() {
        PackageManager pm = getPackageManager();
        List<ResolveInfo> activities = pm.queryIntentActivities(new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH), 0);
        if (activities.size() == 0) {
            Toast.makeText(this, "Recognizer not present", Toast.LENGTH_SHORT).show();
//            speak.setText();
        }

//        imgVoiceSearch.setOnClickListener(v ->
//
//         displaySpeechRecognizer());

    }

    /**
     * Fire an intent to start the voice recognition activity.
     */
//    private void startVoiceRecognitionActivity() {
//
//
//        Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
////        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
//        intent.putExtra(RecognizerIntent.EXTRA_PROMPT, "Voice searching...");
//        if(intent.resolveActivity(getPackageManager())!=null)
//        {
//            startActivityForResult(intent, REQUEST_CODE);
//        }
//        else{
//            Toast.makeText(this, "Your Device Don't Support Speech Input", Toast.LENGTH_SHORT).show();
//        }
//
//    }
//
//    @Override
//    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
//        if (requestCode == RESULT_OK && null != data) {
//            // Populate the wordsList with the String values the recognition engine thought it heard
//            final ArrayList<String> matches = data.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
////            if (!matches.isEmpty()) {
////                String Query = matches.get(0);
//                edSearch.setText(matches.get(0));
////            }
//        }
//        super.onActivityResult(requestCode, resultCode, data);
//    }
//    private void displaySpeechRecognizer() {
//        Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
//        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
//                RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
//// This starts the activity and populates the intent with the speech text.
//        startActivityForResult(intent, SPEECH_REQUEST_CODE);
//    }
//
//    // This callback is invoked when the Speech Recognizer returns.
//// This is where you process the intent and extract the speech text from the intent.
//    @Override
//    protected void onActivityResult(int requestCode, int resultCode,
//                                    Intent data) {
//        if (requestCode == SPEECH_REQUEST_CODE && resultCode == RESULT_OK) {
//            List<String> results = data.getStringArrayListExtra(
//                    RecognizerIntent.EXTRA_RESULTS);
//            String spokenText = results.get(0);
//            edSearch.setText(spokenText);
//            // Do something with spokenText.
//        }
//        super.onActivityResult(requestCode, resultCode, data);
//    }
    //#neovifyissueresolved28sep
    private void getChannelList() {
        layoutSearch.setVisibility(View.VISIBLE);
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call = apiInterface.getChannelList("Bearer " + authToken);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
                try {

                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {

                        List<Datum> datumList = response.body().getData();

                        for (int i = 0; i < datumList.size(); i++) {
                            Long channelId = datumList.get(i).getId();
                            String channelTitle = datumList.get(i).getStationName();
                            String channelLogo = datumList.get(i).getLogo();
                            String videoUrl = datumList.get(i).getUrl();

                            searchList.add(new SearchModel(channelId, channelTitle, channelLogo,videoUrl));
                        }
                        int rvWidth = getScreenWidth() - dpToPx(30);
                        searchAdapter = new SearchAdapter(SearchActivity.this, rvWidth ,searchList, SearchActivity.this);
                        rvSearch.setLayoutManager(new GridLayoutManager(SearchActivity.this, 4));
                        rvSearch.setAdapter(searchAdapter);

                        edSearch.setEnabled(true);

                    } else if (response.code() == 401) {
                        if(response.errorBody()!=null){
                            edSearch.setEnabled(false);
                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(SearchActivity.this);
                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            sessionExpiredDialog.setCancelable(false);
                            sessionExpiredDialog.show();


                        }


                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(SearchActivity.this);
                        CommonUtility.showToast(SearchActivity.this, ConstantUtility.SESSION_EXPIRED);
                        SearchActivity.this.startActivity(new Intent(SearchActivity.this, LogInActivity.class));
                        SearchActivity.this.finish();
                        edSearch.setEnabled(false);

                    } else if (response.code() == 500) {
                        CommonUtility.showToast(SearchActivity.this, ConstantUtility.SERVER_ISSUE);
                        edSearch.setEnabled(false);

                    }
                    progressBar.setVisibility(View.INVISIBLE);
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                    edSearch.setEnabled(false);
                }
                progressBar.setVisibility(View.INVISIBLE);
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                CommonUtility.showToast(SearchActivity.this, ConstantUtility.SERVER_ISSUE);
                progressBar.setVisibility(View.INVISIBLE);
                rvSearch.setVisibility(View.GONE);
                layoutInternet.setVisibility(View.VISIBLE);
                edSearch.setEnabled(false);
            }
        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        networkStateReceiver.removeListener(this);
        this.unregisterReceiver(networkStateReceiver);
        speechRecognizer.destroy();
    }

    @Override
    public void networkAvailable() {
        layoutInternet.setVisibility(View.INVISIBLE);
        getChannelList();

    }
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == RecordAudioRequestCode && grantResults.length > 0 ){
            if(grantResults[0] == PackageManager.PERMISSION_GRANTED)
                Toast.makeText(this,"Permission Granted",Toast.LENGTH_SHORT).show();
        }
    }
    @Override
    public void networkUnavailable() {
        layoutSearch.setVisibility(View.INVISIBLE);
        layoutInternet.setVisibility(View.VISIBLE);

    }

    public int getScreenWidth() {
        DisplayMetrics displayMetrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        int height = displayMetrics.heightPixels;
        int width = displayMetrics.widthPixels;
        return width;
    }

    public int dpToPx(int dp) {
        DisplayMetrics displayMetrics = this.getResources().getDisplayMetrics();
        return Math.round(dp * (displayMetrics.xdpi / DisplayMetrics.DENSITY_DEFAULT));
    }
}