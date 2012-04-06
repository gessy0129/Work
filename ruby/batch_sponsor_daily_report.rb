# coding: utf-8
module Batch
  class BatchSponsorDailyReport
    def self.run
      cam = Campaign.find_all_by_sponsor_id(129)
      cam.each do |c|
        ad = Ad.find_all_by_campaign_id(c.id)

        ad.each do |a|
          for date in '2012.2.20'.to_date..'2012.4.18'.to_date do
            begin
              s = SponsorDailyReport.new
              s.network_id  = 32
              s.sponsor_id  = 129
              s.campaign_id = c.id
              s.ad_id       = a.id
              s.count_sum   = rand(10000)
              s.click_sum   = rand(100)
              s.conversion_sum  = rand(10)
              s.report_date = date.to_date
              s.carrier_type_id = 5
              s.save!
            end
          end
        end
      end
    end
  end
end
