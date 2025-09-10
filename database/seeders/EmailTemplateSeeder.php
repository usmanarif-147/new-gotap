<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplate::insert([
            [
                'name' => 'blank',
                'html' => '
                        <table cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;padding:30px;">
                            <tr>
                                <td>
                                </td>
                            </tr>
                        </table>
                ',
            ],
            [
                'name' => 'Welcome',
                'html' => '
                        <table cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;padding:30px;">
                            <tr>
                                <td>
                                    <h2 style="color:#333;">Welcome to Our Platform</h2>
                                    <p style="color:#666;font-size:15px;">We’re excited to have you on board. Click below to get started with your account.</p>
                                    <a href="#" style="display:inline-block;margin-top:15px;background:#007bff;color:#fff;padding:12px 20px;border-radius:4px;text-decoration:none;">Get Started</a>
                                </td>
                            </tr>
                        </table>',
            ],
            [
                'name' => 'Promotion',
                'html' => '
                        <table cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;padding:30px;">
                            <tr>
                                <td>
                                    <h2 style="color:#d63384;">Exclusive Deal Just for You</h2>
                                    <p style="color:#555;font-size:15px;">Enjoy 25% off your next order. This deal won’t last long, so act fast!</p>
                                    <a href="#" style="display:inline-block;margin-top:15px;background:#d63384;color:#fff;padding:12px 20px;border-radius:4px;text-decoration:none;">Shop Now</a>
                                </td>
                            </tr>
                        </table>',
            ],
            [
                'name' => 'Newsletter',
                'html' => '
                        <table cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;padding:30px;">
                            <tr>
                                <td>
                                    <h2 style="color:#2c3e50;">📬 Monthly Newsletter</h2>
                                    <p style="color:#555;font-size:14px;">Here’s what we’ve been up to lately. Lots of exciting updates and features for you.</p>
                                    <ul style="color:#555;font-size:14px;line-height:1.6;">
                                        <li>New dashboard features</li>
                                        <li>Upcoming webinar</li>
                                        <li>Tips to boost your productivity</li>
                                    </ul>
                                    <p style="margin-top:15px;">Thanks for staying with us!</p>
                                </td>
                            </tr>
                        </table>',
            ],
            [
                'name' => 'Feedback Request',
                'html' => '
                        <table cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;padding:30px;">
                            <tr>
                                <td>
                                    <h2 style="color:#333;">We’d Love Your Feedback</h2>
                                    <p style="color:#555;font-size:15px;">Your opinion matters. Let us know how we’re doing by filling out a quick survey.</p>
                                    <a href="#" style="display:inline-block;margin-top:15px;background:#28a745;color:#fff;padding:12px 20px;border-radius:4px;text-decoration:none;">Give Feedback</a>
                                </td>
                            </tr>
                        </table>',
            ],
            [
                'name' => 'template4',
                'html' => '<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; font-family: Arial, Helvetica, sans-serif;">
                        <!-- Header Section -->
                        <div style="padding: 20px; text-align: center;">
                            <img src="https://images.benchmarkemail.com/client597226/image16012414.png" 
                                alt="Company Logo" style="max-width: 54px; display: block; margin: 0 auto;">
                            
                            <h1 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 40px; font-weight: bold; color: #000000; line-height: 1.25; margin: 10px 0;">Connect.</h1>
                            <h1 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 40px; font-weight: bold; color: #000000; line-height: 1.25; margin: 10px 0;">Collaborate.</h1>
                            <h1 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 40px; font-weight: bold; color: #000000; line-height: 1.25; margin: 10px 0;">Succeed.</h1>
                            
                            <p style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.5; color: #383838; margin: 15px 0;">Unlock New Opportunities at Business Connections!</p>
                            
                            <img src="https://images.benchmarkemail.com/client597226/image16012423.png" 
                                alt="Business networking event" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                        </div>
                        
                        <!-- Body Section -->
                        <div style="background-color: #F1F1F1; padding: 20px;">
                            <p style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.5; color: #383838; margin: 15px 0;">
                                We are pleased to invite you to Business Connections, an event designed to foster 
                                networking and collaboration among professionals from various sectors. The event 
                                will be held on <strong>Thursday, August 10th at 7:00 PM</strong> at the 
                                Innovation Hotel, 56 Business Avenue, Business City.
                            </p>
                            
                            <a href="#" style="display: inline-block; background-color: #000000; color: #ffffff !important; text-decoration: none; padding: 20px 40px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; border: 1px solid #000000; margin: 20px 0;">REGISTER NOW</a>
                            
                            <h2 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 25px; font-weight: bold; color: #000000; line-height: 1.5; margin: 20px 0;">THIS EVENT<br>WILL FEATURE:</h2>
                            
                            <!-- Feature 1: Structured Networking Sessions -->
                            <table style="width: 100%; margin: 30px 0;">
                                <tr>
                                    <td style="vertical-align: top; width: 50%; padding: 10px;">
                                        <h3 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 25px; font-weight: bold; color: #000000; line-height: 1.5; margin-bottom: 10px;">Structured Networking Sessions</h3>
                                        <p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #828282;">
                                            Engage in purposeful and organized networking sessions tailored to help you 
                                            make meaningful connections and expand your professional circle.
                                        </p>
                                    </td>
                                    <td style="vertical-align: top; width: 50%; padding: 10px; text-align: right;">
                                        <img src="https://images.benchmarkemail.com/client597226/image16012552.png" 
                                            alt="Networking sessions" style="max-width: 270px; height: auto; display: block;">
                                    </td>
                                </tr>
                            </table>
                            
                            <img src="https://images.benchmarkemail.com/client597226/image16012557.png" 
                                alt="Event highlights" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            
                            <!-- Feature 2: Industry Leaders -->
                            <table style="width: 100%; margin: 30px 0;">
                                <tr>
                                    <td style="vertical-align: top; width: 50%; padding: 10px; text-align: left;">
                                        <img src="https://images.benchmarkemail.com/client597226/image16012578.png" 
                                            alt="Industry leaders" style="max-width: 270px; height: auto; display: block;">
                                    </td>
                                    <td style="vertical-align: top; width: 50%; padding: 10px;">
                                        <h3 style="font-family: Georgia, Times, \'Times New Roman\', serif; font-size: 25px; font-weight: bold; color: #000000; line-height: 1.5; margin-bottom: 10px;">Opportunities to Meet Industry Leaders</h3>
                                        <p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #828282;">
                                            Connect with established professionals and thought leaders who can provide 
                                            valuable insights and potential collaboration opportunities.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <img src="https://images.benchmarkemail.com/client597226/image16012557.png" 
                                alt="Professional networking" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                            
                            <p style="font-family: Helvetica, Arial, sans-serif; font-size: 20px; line-height: 1.5; color: #828282; margin: 20px 0;">
                                Take advantage of this opportunity to expand your professional network and 
                                discover new collaboration opportunities.
                            </p>
                            
                            <a href="#" style="display: inline-block; background-color: #000000; color: #ffffff !important; text-decoration: none; padding: 20px 40px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; border: 1px solid #000000; margin: 20px 0;">REGISTER NOW</a>
                        </div>
                        
                        <!-- Footer Section -->
                        <div style="background-color: #ffffff; color: #666666; font-size: 11px; line-height: 1.4; text-align: center; padding: 20px;">
                            <!-- Social Media Icons -->
                            <div style="text-align: center; padding: 20px 0;">
                                <table align="center" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                    <td style="padding: 0 5px;">
                                        <a href="#" style="background-color: #000000; padding: 5px; display: inline-block;">
                                        <img src="https://ui.benchmarkemail.com/images/editor/socialicons/fb_btn.png" alt="Facebook" style="width: 24px; height: 24px; display: block;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="#" style="background-color: #000000; padding: 5px; display: inline-block;">
                                        <img src="https://ui.benchmarkemail.com/images/editor/socialicons/tw_btn3.png" alt="Twitter" style="width: 24px; height: 24px; display: block;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="#" style="background-color: #000000; padding: 5px; display: inline-block;">
                                        <img src="https://ui.benchmarkemail.com/images/editor/socialicons/yt_btn.png" alt="YouTube" style="width: 24px; height: 24px; display: block;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 5px;">
                                        <a href="#" style="background-color: #000000; padding: 5px; display: inline-block;">
                                        <img src="https://ui.benchmarkemail.com/images/editor/socialicons/in_btn.png" alt="Instagram" style="width: 24px; height: 24px; display: block;">
                                        </a>
                                    </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Footer Text -->
                            <p>
                                This message was sent to {{EMAIL}} by<br>
                                {{COMPANY_ADDRESS}}<br>
                                <br>
                                <a href="{{UNSUBSCRIBE_LINK}}">Unsubscribe</a> | 
                                <a href="{{PREFERENCES_LINK}}">Update Preferences</a>
                            </p>
                            
                            <!-- Company Logo -->
                            <div style="margin-top: 20px;">
                                <a href="https://www.gotaps.me" target="_blank">
                                    <img src="https://gotaps.me/wp-content/uploads/elementor/thumbs/gotapsteamogo-01-qtydkv2uzd3td1npqjhktrxddysnvut68yoj19yfi8.png" 
                                        alt="GoTaps" style="max-width: 116px;">
                                </a>
                            </div>
                        </div>
                    </div>',
            ]
        ]);
    }
}
