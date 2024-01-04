<?php

class Secret
{
    public static function getGoogleApiKey()
    {
        return 'AIzaSyBOPcINZBcaBGwoMglLALCph7YtAGRkv84';
    }

    public static function getSpreadsheetId()
    {
        // return '19Zb7X3Xm3k9HChqB1nPX0F8H2xaMHU1Qc8agWj95mRo'; // dev id

        return '1nrMb4SK76iHWr3-EF_1viggQdsmdUdCyg5N6wedRNaY'; //live sheet
    }

    public static function referringCredentials()
    {
        return ['LCSW-C', 'LCSW', 'LMSW', 'LBSW', 'LCPC', 'LGPC', 'LCADC', 'LGADC', 'CAC-AD', 'CSC-AD', 'ADT', 'LCMFT', 'LGMFT', 'CRNP-F', 'CRNP-PMH', 'MD', 'RN', 'LMSW intern', 'LGPC intern', 'CNS-PMH / APRN-PMH', 'CRNP intern', 'PhD Psychologist', 'Psychologist Associate'];
    }

    public static function getCliniciansSheetDetail()
    {
        return [
            'sheetName' => 'SECTION 1: CLINICIANS INFORMATION',
            'keys' => ['firstName', 'lastName', 'credentials', 'organization', 'phone', 'email', 'agencyNpi', 'service']
        ];
    }

    public static function getClientSheetDetail()
    {
        return [
            'sheetName' => 'SECTION 2: CONSUMER INFORMATION ',
            'keys' => ['firstName', 'lastName', 'dob', 'gender', 'diagnosis', 'Diagnosis-DB', 'medication', 'medicationName', 'test', 'test4', 'clientMedicaidNumber', 'clientSocialSecurityNumber', 'address', 'test1', 'schoolGrade']
        ];
    }


    public static function saticText()
    {
        return [
            'medicationNoRadio' => 'The consumer is ongoing treatment. Medication need, or want is still being assessed to determine if it will be the best fit for the consumer.',

            'adultCheckobox' => [
                'adult1' => "Marked inability to establish or maintain independent competitive employment characterized by an established pattern of unemployment; underemployment; or sporadic employment that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.",

                'adult2' => "Marked inability to establish or maintain independent competitive employment characterized by an established pattern of unemployment; underemployment; or sporadic employment that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.",

                'adult3' => "Marked inability to perform instrumental activities of daily living (shopping; meal preparation; laundry; basic housekeeping; medication management; transportation; and money management) that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.",

                'adult4' => "Marked or frequent deficiencies of concentration; persistence or pace that is primarily attributable to a serious mental illness resulting in a failure to complete in a timely manner tasks commonly found in work; school; or home settings; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations",

                'adult5' => "Marked deficiencies in self-direction; characterized by an inability to independently plan; initiate; organize; and carry out goal-directed activities that is primarily attributable to a serious mental illness; and which requires intervention by the behavioral health system beyond what can be reasonably provided by mainstream workforce development; educational; faith-based; community or social service organizations",
                'adult6' => "Marked inability to procure financial assistance to support community living; which inability is primarily attributable to a serious mental illness; and which requires intervention by the behavioral health system beyond what can be reasonably provided by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver’s license due to legal problems."
            ],
            "minorCheckobox" => ["minor1" => "A clear, current threat to the youth's ability to be maintained in their customary setting?", "minor2" => "An emerging risk to the safety of the youth or others?", "minor3" => "Significant psychological or social impairments causing serious problems with peer relationships and/or family members?"],

            "minor1" => [
                "minorCheckbox1" => "one",

                "minorCheckbox2" => "two ",
                "minorCheckbox3" => "three"
            ],
            "minor2" => [
                "minorCheckbox1" => "The client is prone to mood swings and self-harming behavior, which makes her hostile toward others. She developed self-harming behavior at an early age, which was brought on by the anxiety she experienced.",

                "minorCheckbox2" => "The consumer has  serious problems in school are described. Significant behavioral problems are present, she has irritability and anger management issue. ",
                "minorCheckbox3" => "
            The client becomes more violent, and there are presently rumors that he physically assaulted another child at school. The client reported that anyone nearby, including friends, classmates, relatives, or strangers, easily irritates him",

                "minorCheckbox4" => "Due to his inability to control his wrath, the client finds it difficult to sustain relationships. Anger poses a severe difficulty for the client's ability to function academically; as a result, he frequently causes trouble at school and receives penalties. client's Social skills have declined. he stated that he has been experiencing random spells of depression and feels alone.",

                "minorCheckbox5" => "The client has made an attempt at suicide. he has tried suicide by hanging and by using a computer cable. He set his hand o fire in an attempt to commit suicide. Self inflicted bleeding to death was used in a suicide attempt. He attempted suicide by asphyxiation.",

                "minorCheckbox6" => "The consumer made a suicide attempt by mixing different pills and ingesting them and  self cutting behavior is reported",

                "minorCheckbox7" => "The client becomes aggressive with others like using harsh and angry words, she has angry verbal outbursts and gets irritable easily with friends, school mates, family or whoever is around her. She has destroyed property. The client symptoms occur 2-3 times a month,her anger escalates quickly, within minutes and lasts for hours. Client reports she has been self injurious.  Self cutting behavior, without suicidal intent, is reported.  The last occurrence of self injurious behavior was  march 2023 and this is brought on by Anxiety and depression.",
                "minorCheckbox8" => "The client becomes aggressive with others because of his Disruptive Mood Dysregulation Disorder starting when the age of 13. He exhibited aggressive behaviors.  He was destructive of property. He was involved in frequent violation of rules. He reported that he has made a suicidal attempt.  He made a suicide attempt by overdose brought on by depression and his depression was first diagnosed when he was age of 13. ",
                "minorCheckbox9" => "Client has a history of becoming aggressive with others as well as history of cutting due to depression."
            ],
            "minor3" => [
                "minorCheckbox1" => "Client experiences difficulty maintaining relationships with peers and family members due to her depression and anxiety.  She continues to experience sadness, feelings of loneliness, unhappy and withdrawn socially.",

                "minorCheckbox2" => "The customer describes significant or ongoing worry as interfering with his ability to function. There are serious issues with education described. There are significant academic challenges. The consumer is worried about Mom's illness. There have been reports of compulsive habits like rituals. There are reports of impulsive or unpredictable behavior. Strong or unique concerns, such as those related to heights, insects, crowds, germs, or open spaces, have been plaguing the consumer. Consumers are sometimes uncooperative, slow to reply, and inattentive.",

                "minorCheckbox3" => "Client experiences difficulty maintaining relationships with peers and family members due to her depression and anxiety.  She continues to experience sadness, feelings of loneliness, unhappy and withdrawn socially.",

                "minorCheckbox4" => "The following psychological signs indicate that the customer has trouble maintaining relationships with others. Focus is easily lost in a noisy classroom. Additionally, Clayton's mother stated that when there is too much movement around him, he is quickly distracted.  Inattentional symptoms are present in Clayton. He can't focus for very long. There are reports of attentional issues in the school, but not hyperactivity. The customer has an IEP and faces academic difficulties.",

                "minorCheckbox5" => "The client was tormented and publicly humiliated, she has trouble forming relationships. She exhibits inconsistent conduct, shows little motivation, lacks a great deal of self-confidence, is socially awkward as a result of personality conflicts, and expresses a desire to die because she is humiliated and embarrassed of herself. She discusses how a binge might leave you feeling depressed, guilty, or unsettled. She experiences intense sadness. She feels solitary. Her inability to sleep is problematic. These depression symptoms are preventing her from functioning on a daily basis.",

                "minorCheckbox6" => "Due to the consumer's depression illness symptoms, the patient finds it difficult to maintain friendships.",

                "minorCheckbox1" => "The client has trouble keeping up with relationships. She admitted to having a terrible time in school and that she was a bad influence on others. She also expressed her deep concerns about education. A significant issue with the relationships has come to emerge due to her fear and hopelessness.",

                "minorCheckbox7" => "The client admits that his ongoing conflict with peers makes it difficult for him to remain motivated to finish his academic work. Additionally, the client has verbally abusive outbursts and is more likely to become irascible around friends, teachers, family, or other people.",

                "minorCheckbox8" => "The consumer finds it challenging to build relationships with others since they continue to exhibit symptoms of an emotional disease that make it challenging for him to carry out daily activities.",

                "minorCheckbox9" => "The client continues to struggle with symptoms of mood dysregulation and ADHD.  Serious problem continue with peers, she  was put out of camp for threatening to stab another child and called the police from the school's phone.  Her anger continues to interfere with educational functioning and peer interaction.  While there are moments she is doing better she continues to exhibit symptoms that are unchanged.",

                "minorCheckbox10" => "The consumer has difficulty maintaining relationships  with others because",

                "minorCheckbox11" => "The patient has difficulty maintaining relationships  with others because of the following depressive symptoms like  irritability,  Increased Worrying,  consumer  has mood swings and  has periods of increased energy and  happiness. Jaelyn exhibits symptoms of anxiety. Her anxiety symptoms have been present for 2-3 years.  Jaelyn's reported depressive symptoms are as follows: Loss of energy, fatigue, concentration difficulties, increased worrying, interferes with daily functioning, isolation, describes irritability. Jaelyn was hospitalized on one occasion. She was first hospitalized as an adolescent.   Destructive I drank a bottle of alcohol. Jaelyn has made suicidal attempts.  She made a suicide attempt by overdose, age 12 she did not tell anyone about it. Jaelyn has been self injurious. Self cutting behavior, without suicidal intent, is reported. Jaelyn has a history of possible alcohol over use. anything I find laying out started age 12 last drank December 2022 has abused alcohol 1/4 to 1/2 liter has smoked marijuana several times last time 2022 has had edibles.",

                "minorCheckbox12" => "Client has difficulty maintaining a relationship because her anger management problems have interfered with the following: She has lost friends because of her anger. Her relationships have been complicated or injured because of difficulty controlling anger. She acts out. She is angry and has outbursts of anger periodically. Her appetite has decreased. She has difficulty concentrating. She is often fatigued. Feelings of worthlessness are present. There has been an increase in worrying. These depressive symptoms interfere with her daily functioning. She feels isolated. She experiences heavy sadness. She describes irritability. She has trouble sleeping. She experiences intense anxiety or fear of being judged, negatively evaluated, or rejected in a social or performance situation brought on by Anxiety and Depression.",

                "minorCheckbox13" => "Client has difficulty maintaining a relationship he expressed exhibits a marked and sustained impairment of social interaction. He avoids looking directly at other people and does not smile at appropriate cues. A serious relationship problem is reported. He is experiencing significant relationship strife. He reports currently he has an issue with father he stated being treated differently. Serious problems in school are described. Significant academic difficulties are present. Significant behavioral problems are present. He experiences heavy sadness. He feels isolated. He is angry and has outbursts of anger periodically. His appetite has decreased. He has difficulty concentrating due to anxiety and depression.",

                "minorCheckbox14" => "Client has history of maintaining relationships because of her depressed mood, she experiences symptoms of isolation, sadness and trouble sleeping.  She has trouble making friends and communicating her thoughts and feelings to her mother due to feeling invalidated.  History of cutting behavior, reported because he wanted her mother to realize she was serious about needing help.",

                "minorCheckbox15" => "The client continues to struggle with symptoms, social isolation and experiences panic attacks multiple times per day brought on by anxiety. She describes excessive or constant worrying that interferes with her functioning. She reports that it has been present for years. Difficulty concentrating is a problem. Shayla's panic attacks occur with the acute onset of a discrete period of intense fear or discomfort. Affects educational functioning.  She has chills or hot flashes. Derealization is experienced. Her heart rate increases. Nausea or abdominal distress occurs. She often trembles or shakes. Shayla has a history of suicidal thoughts but has never made an attempt.",

                "minorCheckbox16" => "Due to ongoing symptoms like social anxiety, the client finds it challenging to maintain relationships. This makes it tough for him to create and sustain a social network of friends and acquaintances. On average, the client experiences recurring psychotic symptoms a couple times per week. His first psychotic symptoms appeared when he was 14 years old and slowly worsened over several years.",

                "minorCheckbox17" => "The client continues to struggle with symptoms, social isolation, and is in need of living skills. Serious problems in school are described regarding social anxiety. Personality conflicts are creating social problems for the client.",

                "minorCheckbox18" => "Consumer experiences depression and anxiety and isolates from family and friends. Consumer does not have a good relationship with peers at school. When anxiety and depression are present, consumer has angry outbursts and this affects her relationships at school and her home environment. Consumer frequently experiences low motivation and this causes her to have marginal grades in school. ",

                "minorCheckbox19" => "Consumer expresses anger, explosive and impulsive behavior with family. Madison has exhibited anger problems. When angry, she expresses it with angry words and an angry demeanor.  She uses harsh and angry words. Angry verbal outbursts have been reported. These outbursts have negatively impacted her home environment and has caused tension and discord. She has also become less social with family members and friends, as a result. ",

                "minorCheckbox20" => "The client often loses his temper and frequently argues with adults. He exhibits active defiance or refusal to comply with rules. He blames others for his mistakes or misbehaviors. He is easily agitated or annoyed by others."
            ],
            "ansOfQuestion21" => "Client has been engaging in regular outpatient treatment and has made minimal progress. Additional supports are needed."
        ];
    }
}
