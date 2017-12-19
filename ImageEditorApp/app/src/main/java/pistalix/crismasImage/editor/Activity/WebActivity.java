package pistalix.crismasImage.editor.Activity;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

import pistalix.crismasImage.editor.R;
import pistalix.crismasImage.editor.Subfile.Glob;


public class WebActivity extends AppCompatActivity {

    private WebView webPrivacyPolicy;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_web);

        webPrivacyPolicy = (WebView) findViewById(R.id.wvPrivacyPolicy);

//        webPrivacyPolicy.getSettings().setJavaScriptEnabled(true); // enable javascript
        WebSettings webSettings = webPrivacyPolicy.getSettings();
        webSettings.setJavaScriptEnabled(true);// enable javascript
        webSettings.setJavaScriptCanOpenWindowsAutomatically(true);

        webPrivacyPolicy.setWebViewClient(new WebViewClient() {

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                if( url.startsWith("http:") || url.startsWith("https:") ) {
                    return false;
                }

                // Otherwise allow the OS to handle things like tel, mailto, etc.
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                startActivity( intent );
                return true;
            }


            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Toast.makeText(WebActivity.this, description, Toast.LENGTH_SHORT).show();
            }
        });

        webPrivacyPolicy.loadUrl(Glob.privacy_link);
    }

    @Override
    public void onBackPressed() {
        finish();
    }




}
