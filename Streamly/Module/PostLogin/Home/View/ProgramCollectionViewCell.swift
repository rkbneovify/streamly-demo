//
//  ProgramCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ProgramCollectionViewCell: UICollectionViewCell {
    
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var gradientView: UIView!
    @IBOutlet weak var titleLabel: UILabel!
    @IBOutlet weak var liveShowView: UIView!
    @IBOutlet weak var liveViewTraling: NSLayoutConstraint!
    override func awakeFromNib() {
        super.awakeFromNib()
        self.setup()
        gradientView.layer.cornerRadius = 8
        gradientView.clipsToBounds = true
        // Initialization code
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        //  self.setup()
    }

    override init(frame: CGRect) {
        super.init(frame: frame)
        self.setup()
    }

    private func setup()
    {
        gradientView.borderWidth = 1.0
        gradientView.borderColor = UIColor.white
        gradientView.backgroundColor = UIColor.clear
    }

    private func comapreTime(_ timstamp:TimeInterval)
    {
        

        let firstDate = Utility.getCurrentDateTime()
        //print(firstDate)
        let secondDate = (Utility.getDateFromTimeStampByFormat(from: timstamp , "dd/MM/yyyy hh:mm a") ?? "")

        if  (firstDate.toDate()?.compare(secondDate.toDate()!) == .orderedDescending){
            self.timeLabel.textColor  = UIColor.white.withAlphaComponent(0.6)
            self.titleLabel.textColor  = UIColor.white.withAlphaComponent(0.6)
            self.gradientView.borderColor = UIColor.white.withAlphaComponent(0.6)

        }
        else {
            self.timeLabel.textColor  = UIColor.white
            self.titleLabel.textColor  = UIColor.white
            self.gradientView.borderColor = UIColor.white
        }
    }

    private func calculateTimeDiffrence(_ start:TimeInterval, _ end:TimeInterval) -> Bool
    {
        guard let currentTime = Utility.getCurrentDateTime().toDate() else {return false}
        guard let startDate = (Utility.getDateFromTimeStampByFormat(from: start , "dd/MM/yyyy hh:mm a") ?? "").toDate() else {return false}
        guard let endDate = (Utility.getDateFromTimeStampByFormat(from: end , "dd/MM/yyyy hh:mm a") ?? "").toDate() else {return false}
      
        let currentDate = 1440*Calendar.current.component(.day, from: currentTime) + 60*Calendar.current.component(.hour, from: currentTime) + Calendar.current.component(.minute, from: currentTime) + (Calendar.current.component(.second, from: currentTime)/60) // in minutes
        let time1 = 1440*Calendar.current.component(.day, from: startDate) + 60*Calendar.current.component(.hour, from: startDate) + Calendar.current.component(.minute, from: startDate) + (Calendar.current.component(.second, from: startDate)/60) // in minutes
        let time2 =  1440*Calendar.current.component(.day, from: endDate) + 60*Calendar.current.component(.hour, from: endDate) + Calendar.current.component(.minute, from: endDate) + (Calendar.current.component(.second, from: endDate)/60) // in minutes

        if (currentDate > time1 && currentDate < time2) {
            return true
        } else {
            return false
        }

    }


    func configureWithModel(program: ProgramArray,row : Int)
    {
        if program.title?.lowercased() == "no broadcast" {
            print(program.title ?? "")
        }
        self.comapreTime(program.end ?? 0)
        self.liveShowView.isHidden = !(self.calculateTimeDiffrence(program.start ?? 0,program.end ?? 0))
        if !self.liveShowView.isHidden
        {
            let cellWidth = Utility.getProgrammWidth(start: program.start ?? 0, end: program.end ?? 0)
            guard let d1 = Utility.getCurrentDateTime().toDate() else {return}
            guard let startDate = (Utility.getDateFromTimeStampByFormat(from: program.start ?? 0 , "dd/MM/yyyy hh:mm a") ?? "").toDate() else {return}
            let intervals = d1.timeIntervalSince(startDate)
            let numberOfHours = CGFloat(intervals / 900)
            let width = CGFloat(numberOfHours) * (408 * 2)
            self.liveViewTraling.constant = cellWidth - width
                ////print(width)
        }
        else  {
            self.liveViewTraling.constant = 0.0
        }
       
        self.titleLabel.text = program.title ?? ""
        self.timeLabel.text = "\(Utility.getDateFromTimeStampByFormat(from: program.start ?? 0.0) ?? "") - \(Utility.getDateFromTimeStampByFormat(from: program.end ?? 0.0) ?? "")"

    }
    
    func emptyCell() {
        let container: UIView = UIView()
        container.frame = CGRect(x: 0, y: 0, width: 80, height: 80) // Set X and Y whatever you want
        container.backgroundColor = .clear
        
        let activityView = UIActivityIndicatorView(style: .medium)
        activityView.center = self.center
        
        container.addSubview(activityView)
        self.addSubview(container)
        self.bringSubviewToFront(container)
        activityView.startAnimating()
    }

    // MARK: UIFocusEnvironment

    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
                self.setup()
            }
            else {

                self.gradientView.borderWidth = 0.0
                self.gradientView.backgroundColor = UIColor.init(red: 0/255, green: 33/255, blue: 82/255, alpha: 1)


                
            }
        }, completion: nil)
    }
}

